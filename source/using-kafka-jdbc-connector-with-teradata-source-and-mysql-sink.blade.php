<!--
{{ $page_title = 'Using Kafka JDBC Connector with Teradata Source and MySQL Sink' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Using Kafka JDBC Connector with Teradata Source and MySQL Sink', 'sub_txt' => 'Posted on Feb 14, 2017 at 5:15 pm'])

<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">

<p>This post describes a recent setup of mine exploring the use of Kafka for pulling data out of Teradata into MySQL. Recent versions of Kafka provide purpose built connectors that are extremely useful in both retrieving data from source systems and push data to other platforms.</p>

<p>I won't go into the details of setting up Kafka but I will touch briefly on setting up the JDBC drivers for Teradata and MySQL. If your not familar with Teradata it is an enterprise data warehouse system, Kafka connectors for JDBC operations can connect to many different JDBC supported systems. This post could easily be adapted to support operations from various other databases.</p>

<hr>

<h4>Kafka Configuration and Setup</h4>

<p>Lets get started by kicking of Kafka, Zookeep, and the Schema Registry:</p>

<pre><code class="bash">sudo -s
cd /usr/bin
./zookeeper-server-start -daemon /etc/kafka/zookeeper.properties
./kafka-server-start -daemon /etc/kafka/server.properties
./schema-registry-start -daemon /etc/schema-registry/schema-registry.properties
</code></pre>

<hr>

<h4>Setting up Teradata and MySQL JDBC Connectors</h4>

<p>In order for Kafka to work with Teradata as a data source we need to install the JDBC connector on the server. Follow these steps. Download Teradata JDBC connector from their <a href="http://downloads.teradata.com/download/connectivity/jdbc-driver" target="_blank">website</a>.</p>

<p>Extract the driver somewhere on your system. I put mine in /usr/share/java/kafka-connect-jdbc.</p>

<p>Next up head over to MySQL's website and download the MySQL JDBC connector from this <a href="https://dev.mysql.com/downloads/connector/j/" target="_blank">page</a>.</p> Once you've extracted it, place the contents of the file somewhere. I placed mine at: /usr/share/java/mysql-connector-java to keep things consistent.</p>

<p>Then edit your bash profile to add the JARs path to your Java CLASSPATH variable. This lets other Java programs know where to find them.</p>
<pre><code class="bash">
vi ~/.bash_profile
# add this line
export CLASSPATH="${CLASSPATH}:/usr/share/java/kafka-connect-jdbc/tdgssconfig.jar:/usr/share/java/kafka-connect-jdbc/terajdbc4.jar"
</code></pre>

<pre><code class="bash">source ~/.bash_profile</code></pre>

<p>To make the paths available system wide you should also create a new profile script with this export statements:</p>

<pre><code class="bash">vi /etc/profile.d/kafka.sh</code></pre>

<pre><code class="bash"># teradata jdbc class path and mysql jdbc for kafka
export CLASSPATH="${CLASSPATH}:/usr/share/java/kafka-connect-jdbc/tdgssconfig.jar:/usr/share/java/kafka-connect-jdbc/terajdbc4.jar:/usr/share/java/mysql-connector-java/mysql-connector-java-5.1.40-bin.jar"</code></pre>

<pre><code class="bash"># reload envs
chmod +x /etc/profile.d/kafka.sh
source /etc/profile.d/kafka.sh</code></pre>

<hr>

<h4>Creating a Kafka Connect JDBC Connection (Source)</h4>

<p>After we have the JDBC connector installed on the server we can create a new Kafka connect properties file. This file is passed as an argument to the Kafka Connect program and provides the configuration settings neccessary to connect to the data source.</p>

<p>Setup the kafka connect jdbc custom query for teradata:</p>

<pre><code class="bash">vi /etc/kafka-connect-jdbc/teradata-source.properties</code></pre>

<pre><code class="ini">name=teradata-source
connector.class=io.confluent.connect.jdbc.JdbcSourceConnector
tasks.max=10
batch.max.rows=1000
connection.url=jdbc:teradata://datawarehouse.internaldomain.com/database=DATAWRH,user=MY_USER_NAME,password=MY_PASSWORD_HERE,tmode=ANSI,charset=UTF8
query=SELECT a.ID, a.COL_NAME_1, a.COL_NAME_2, a.LAST_UPDATED_TIMESTAMP FROM DATAWRH.TABLE_NAME a
mode=timestamp+incrementing
timestamp.column.name=LAST_UPDATED_TIMESTAMP
incrementing.column.name=ID
topic.prefix=teradata-source
poll.interval.ms=30000</code></pre>

<p>Notice in the above file you can place a query. This query is important. For my purposes I've instructed the Kafka JDBC Source connector to run a custom query against Teradata. This query will be an incremental query and look for new data based on the tables timestamp and unqiue identifer column. This first run of the connector will pull in all the data into a Kafka topic named "teradata-source", afterwards on each polling interval the connector will look for new data that meets the conditions of having a timestamp and identifier that indicate the data been updated. If your interested in more configuration options look <a href="http://docs.confluent.io/3.0.0/connect/connect-jdbc/docs/configuration_options.html" target="_blank">here</a>.</p>

<p>The next step is to setup the connectors schema properties. Kafka comes with some default property sets. All you need to do is simply copy one of them and rename it and change a few lines.</p>

<pre><code class="bash">cp /etc/schema-registry/connect-avro-standalone.properties /etc/schema-registry/teradata-source.properties</code></pre>

<pre><code class="bash">vi /etc/schema-registry/teradata-source.properties</code></pre>

<pre><code class="ini"># change the storage location to not conflict with other connectors
offset.storage.file.filename=/tmp/teradata-source.offsets
# add this line to set the default port
rest.port=8083</code></pre>

<hr>

<h4>Start the Kafka Connect JDBC Source Connector</h4>

<pre><code class="bash">sudo -s
cd /usr/bin
./connect-standalone -daemon /etc/schema-registry/teradata-source.properties /etc/kafka-connect-jdbc/teradata-source.properties</code></pre>


<p>Notice the "-daemon" argument to the connector, this tells it to run in the background. If you are experiencing problems you can omitt this piece and see the output of the errors in the foreground. You can always use the rest API to see the status of the connector too.</p>

<p>To see what kind of data is being pulled you can start up a Kafka consumer and instruct it to listen in on the topic:</p>

<pre><code class="bash">cd /usr/bin
./kafka-avro-console-consumer --new-consumer --bootstrap-server localhost:9092 --topic teradata-source --from-beginning</code></pre>

<p>To check the status of the connector you can also use the REST api:</p>

<pre><code class="bash">curl localhost:8083/connectors/teradata-source/status | jq</code></pre>

<p>Note the "jq" program that I piped the output to is a nice utility for pretty-printing JSON output on the console. To install it use:</p>

<pre><code class="bash">wget https://github.com/stedolan/jq/releases/download/jq-1.5/jq-linux64
mv jq-linux64 /usr/local/bin/jq
chmod +x /usr/local/bin/jq</code></pre>

<hr>

<h4>Setting up the Kafka JDBC Sink Connector to MySQL</h4>

<p>Now that we have data from Teradata coming into a Kafka topic, lets move that data directly to a MySQL database by using the Kafka JDBC Connector's sink capability. More documentation can be found <a href="http://docs.confluent.io/3.1.1/connect/connect-jdbc/docs/sink_connector.html" target="_blank">here</a>.</p>

<pre><code class="bash">vi /etc/kafka-connect-jdbc/mysql-sink.properties</code></pre>

<pre><code class="bash">name=mysql-sink
connector.class=io.confluent.connect.jdbc.JdbcSinkConnector
tasks.max=1
topics=tech-attributes
connection.url=jdbc:mysql://mysqlserver.internaldomain.com:3306/mysql_db_name_here
connection.user=my_db_user_name
connection.password=my_db_password
pk.mode=record_value
pk.fields=ID
insert.mode=upsert
batch.size=3000
auto.create=false
auto.evolve=false
max.retries=20
table.name.format=tblSomeDataStore</code></pre>

<hr>

<h4>Setup a new property file for the mysql connect sink</h4>

<p>Since we are running multiple standalone instances on the same host, there are a couple of settings that must differ between each instance: offset.storage.file.filename - storage for connector offsets, which are stored on the local filesystem in standalone mode; using the same file will lead to offset data being deleted or overwritten with different values rest.port - the port the REST interface listens on for HTTP requests.</p>

<p>Copy the default to a new file:</p>
<pre><code class="bash">cp /etc/schema-registry/connect-avro-standalone.properties /etc/schema-registry/mysql-sink.properties</code></pre>

<p>Edit the new file:</p>
<pre><code class="bash">vi /etc/schema-registry/mysql-sink.properties</code></pre>

<pre><code class="ini"># changed this:
offset.storage.file.filename=/tmp/mysql-sink.offsets

# added this:
rest.port=8084</code></pre>

<p>The key here is a non-conflicting REST port, and a non-conflicting offset storage location.</p>

<hr>

<h4>Next create the database schema for the results on your MySQL server</h4>

<p>While you don't technically need to create the schema for the data, Kafka has the ability to create it, it's nice to create it manually (auto.create=true) if you want to control the field types and index operations.</p>

<pre><code class="sql">CREATE TABLE `tblSomeDataStore` (
  `ID` bigint(20) NOT NULL,
  `COL_NAME_1` varchar(256) DEFAULT NULL,
  `COL_NAME_2` varchar(256) DEFAULT NULL,
  `LAST_UPDATED_TIMESTAMP` timestamp(3) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `LAST_UPDATED_TIMESTAMP` (`LAST_UPDATED_TIMESTAMP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;</code></pre>

<hr>

<h4>Starting the Kafka Connect JDBC Sink MySQL Connector</h4>

<pre><code class="bash">
cd /usr/bin
./connect-standalone -daemon /etc/schema-registry/mysql-sink.properties /etc/kafka-connect-jdbc/mysql-sink.properties</code></pre>

<p>Your table should start populating with data. You can check the status of the connector with:</p>

<pre><code class="bash">curl localhost:8084/connectors/mysql-sink/status | jq</code></pre>

<hr>

<p>I'm sorry if this post is a little rough on details. I wanted to get my thoughts down while I explore Kafka as part of some data pipelining operations I'm working on. I'm also looking at tools like Apache Flink which can be used to transform data in a Kafka topics, and Airbnb's Airflow which provides the ability do complex workflow driven operations. More to come in future blog posts.</p>

<div id="disqus_thread"></div>
<script>

var disqus_config = function () {
this.page.url = 'http://www.tegdesign.com/using-kafka-jdbc-connector-with-teradata-source-and-mysql-sink';
this.page.identifier = 'using-kafka-jdbc-connector-with-teradata-source-and-mysql-sink';
};

(function() {
var d = document, s = d.createElement('script');
s.src = '//tegdesign.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>



</div>

</div>

</div>

@endsection
