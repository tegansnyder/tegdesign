<!--
{{ $page_title = 'Converting a nested JSON document to CSV using Scala, Hadoop, and Apache Spark' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Converting a nested JSON document to CSV using Scala, Hadoop, and Apache Spark', 'sub_txt' => 'Posted on Feb 13, 2017 at 6:48 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>Usually when I want to convert a JSON file to a CSV I will write a simple script in PHP. Lately I've been playing more with Apache Spark and wanted to try converting a 600MB JSON file to a CSV using a 3 node cluster I have setup. The JSON file itself contains a nested structure so it took a little fiddling to get it right, but overall I'm impressed with the speed of the execution.</p>

<p>So I decided to take the JSON data and put it on the HDFS (Hadoop Filesystem). My setup consists of 3 RHEL 7 boxes running Spark and Hadoop in cluster mode.</p>

<p>So I uploaded some json file containing a bunch of keyword data to my home folder (/home/tegan). The ran the following to move it to HDFS.</p>

<pre><code class="bash">dzdo -s
hdfs dfs -mkdir /keywordData
hdfs dfs -put /tegan/keywordData.json /keywordData</code></pre>

<pre><code class="bash"># verify the folder shows up
hdfs dfs -ls
#verify the file shows up
hdfs dfs -ls /keywordData</code></pre>


<p>Ok then I decided to try a spark-shell and write some scala to convert the JSON to CSV:</p>

<pre><code class="scala">val json_path = "hdfs:///keywordData/keywordData.json"
val df = spark.read.json(json_path)

df.write
    .format("com.databricks.spark.csv")
    .option("header", "true")
    .save("keywordData.csv")</code></pre>

<p>Executing that on the spark-shell caused a java heap error with memory:</p>

<pre><code class="bash">17/02/07 16:00:33 WARN scheduler.TaskSetManager: Lost task 0.0 in stage 0.0 (TID 0, localhost, executor driver): java.lang.OutOfMemoryError: Java heap space</code></pre>

<p>I thought this would be an issue with such a large JSON file, luckily Spark's ability to cluster a job can solve my memory issue. Instead of running this on one server, I can distribute it out to my other worker nodes by compiling the Scala code to a JAR.</p>

<p>To setup the structure of the Scala project i did the following:</p>

<pre><code class="bash">mkdir -p /home/tegan/KeywordData/src/main/scala/com/tegan/spark/keyworddata/
cd /home/tegan/KeywordData/src/main/scala/com/tegab/spark/keyworddata/</code></pre>

<p>Then in the folder I created a KeywordData.scala file with the following in it:</p>

<pre><code class="bash">vi /home/tegan/KeywordData/src/main/scala/com/tegab/spark/keyworddata/KeywordData.scala</code></pre>

<pre><code class="scala">package com.tegan.spark.keyworddata
import org.apache.spark.{SparkConf, SparkContext}
import org.apache.spark.SparkContext._
import org.apache.spark.sql.SparkSession

object KeywordData {
    def main(args: Array[String]) {

        val sparkConf = new SparkConf().setAppName("Keyword Data")
        val sc = new SparkContext(sparkConf)
        val sparkSession = SparkSession.builder.getOrCreate()
        import sparkSession.implicits._

        val json_path = "hdfs://hadoop-master:9000/keywordData/keywordData.json"
        val df = sparkSession.read.json(json_path)

        df.write
            .format("com.databricks.spark.csv")
            .option("header", "true")
            .save("hdfs://hadoop-master:9000/keywordData/keywordData.csv")
    }
}</code></pre>

<p>To compile the Scala above into a JAR I'm using SBT which requires a project file. This is created by switching directories to /home/tegan/KeywordData/ and creating a KeywordData.sbt file.</p>

<pre><code class="bash">cd /home/tegan/KeywordData
vi ExternalSearch.sbt</code></pre>

<pre><code class="bash">name := "KeywordData"

version := "1.0"

scalaVersion := "2.11.8"

libraryDependencies ++= Seq(
  "org.apache.spark" %% "spark-core" % "2.0.2",
  "org.apache.spark" %% "spark-sql" % "2.0.2"
)
</code></pre>

<p>Then compile the Scala project to a JAR with:</p>

<pre><code class="bash">sbt package</code></pre>

<p>When you are ready to submit the submit to Spark issue:</p>

<pre><code class="bash">/opt/spark/bin/spark-submit \
 --class com.tegan.spark.keyworddata.KeywordData \
 --master spark://spark-master:7077 \
 --deploy-mode=client \
 /home/tegan/KeywordData/target/scala-2.11/externalsearch_2.11-1.0.jar</code></pre>

<p>It starts to run for a bit then I get this error:</p>

<pre><code class="bash">Exception in thread "main" java.lang.UnsupportedOperationException: CSV data source does not support array</code></pre>

<p>I'm guessing it is because the JSON data contains a nested format. Here is an example of what it looks like:</p>

<pre><code class="json"> {
    "keyword_data": [{
        "value": "some keyword term here",
        "type": "Keyword Phrase",
        "data_points": [{
            "name": "Sessions",
            "value": 173628
        }, {
            "name": "Users",
            "value": 158454
        }, {
            "name": "Views",
            "value": 221868
        }]
    },{
        "value": "another keyword term here",
        "type": "Keyword Phrase",
        "data_points": [{
            "name": "Sessions",
            "value": 32432
        }, {
            "name": "Users",
            "value": 2333
        }, {
            "name": "Views",
            "value": 3332111
        }]
    }]
}</code></pre>

<p>So a little altercations to the Scala code to read these nested values into a data frame looks like this:</p>

<pre><code class="scala">package com.tegan.spark.keyworddata
import org.apache.spark.{SparkConf, SparkContext}
import org.apache.spark.SparkContext._
import org.apache.spark.sql.SparkSession

object ExternalSearch {
    def main(args: Array[String]) {

        val sparkConf = new SparkConf().setAppName("Keyword Data")
        val sc = new SparkContext(sparkConf)
        val spark = SparkSession.builder.getOrCreate()
        import spark.implicits._

        val json_path = "hdfs://hadoop-master:9000/keywordData/keywordData.json"
        val df = spark.read.json(json_path)
        val df_2 = spark.read.json(json_path)

        var xp_df_1 = df.withColumn("term_flat", explode(df("keyword_data")))
        var xp_df_2 = xp_df_1.drop(xp_df_1.col("keyword_data"))

        var xp_df_data_points = xp_df_2.withColumn("data_points", xp_df_2("term_flat.data_points"))

        var xp_df_name = xp_df_data_points.withColumn("m_guid", xp_df_data_points("measure.name"))
        var xp_df_name_val = xp_df_name.withColumn("m_name", xp_df_name("measure.value"))

        var xp_df_final = xp_df_name_val.drop(xp_df_name_val.col("term_flat"))
        var final_df = xp_df_final.drop(xp_df_final.col("data_points"))

        final_df.write
            .format("com.databricks.spark.csv")
            .option("header", "true")
            .save("hdfs://hadoop-master:9000/keywordData/keywordData.csv")
    }
}</code></pre>

<p>Recompile the new Scala code to a JAR using "sbt package" then submit it again and it should run, placing the final results in the HDFS location called out in the write option above.</p>

<p>The more I play with data, building ETL pipelines, working on marketing data applications, and working hand in hand with sales data, the more power I see to developing a good understanding of tools like Apache Spark, Airbnb's Airflow, and Elastic Stack. More to come!</p>


<div id="disqus_thread"></div>
<script>

var disqus_config = function () {
this.page.url = 'http://www.tegdesign.com/converting-a-nested-json-document-to-csv-using-scala-hadoop-and-apache-spark';
this.page.identifier = 'converting-a-nested-json-document-to-csv-using-scala-hadoop-and-apache-spark';
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
