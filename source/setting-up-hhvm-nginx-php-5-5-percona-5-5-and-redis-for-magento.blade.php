<!--
{{ $page_title = 'Setting up HHVM, Nginx, PHP 5.5, Percona 5.5, and Redis for Magento' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Setting up HHVM, Nginx, PHP 5.5, Percona 5.5, and Redis for Magento', 'sub_txt' => 'Posted on November 11, 2014 at 3:49 am'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>I&#8217;ve had the opportunity to try a variety of different server configurations but never really got around to trying HHVM with Magento until recently. I thought I would share a detailed walkthrough of configuring a single instance Magento server running Nginx + Fast CGI + HHVM / PHP-FPM + Redis + Percona. For the purpose of this blog post I&#8217;m assuming you are using Fedora, CentOS, or in my case RHEL 6.5.</p>
<p><strong>NOTE:</strong> For an updated version visit: <a href="https://gist.github.com/tegansnyder/96d1be1dd65852d3e576">https://gist.github.com/tegansnyder/96d1be1dd65852d3e576</a></p>
<p><strong>Install the EPEL, Webtatic, and REMI repos</strong></p>


<pre><code class="bash">
rpm -Uvh http://download.fedoraproject.org/pub/epel/6/i386/epel-release-6-8.noarch.rpm
rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
rpm -Uvh http://mirror.webtatic.com/yum/el6/latest.rpm
</code></pre>


<p><strong>Install PHP 5.5.18</strong></p>


<pre><code class="bash">
yum -y install php55w php55w-opcache php55w-devel php55w-mcrypt php55w-gd php55w-mbstring php55w-mysql php55w-pdo php55w-soap php55w-xmlrpc php55w-xml php55w-pdo php55w-mysqli libwebp
</code></pre>


<p><strong>Install Percona</strong><br />
Note you may have existing mysql packages installed in your distro. If you do you will need to remove them prior to installing Percona. You can check by issuing:</p>


<pre><code class="bash">
rpm -qa | grep -i mysql
</code></pre>


<p>For instance on my server I needed to remove the following:</p>


<pre><code class="bash">
yum remove mysql
yum remove mysql-libs
yum remove compat-mysql51
</code></pre>


<p><strong>Setup the Percona Repo</strong><br />
Open a VI editor to the following file.</p>


<pre><code class="bash">
vi /etc/yum.repos.d/Percona.repo
</code></pre>


<p>Add the following:</p>


<pre><code class="bash">
[percona]
name = CentOS $releasever - Percona
baseurl=http://repo.percona.com/centos/$releasever/os/$basearch/
enabled = 1
gpgkey = file:///etc/pki/rpm-gpg/RPM-GPG-KEY-percona
gpgcheck = 1
</code></pre>


<p><strong>Grab the Percona GPG key</strong></p>


<pre><code class="bash">
wget http://www.percona.com/downloads/RPM-GPG-KEY-percona
sudo mv RPM-GPG-KEY-percona /etc/pki/rpm-gpg/
</code></pre>


<p><strong>Install Percona via Yum</strong></p>


<pre><code class="bash">
sudo yum install -y Percona-Server-client-56 Percona-Server-server-56 Percona-Server-devel-56
</code></pre>


<p><strong>Start Percona and Setup Root Pass</strong></p>


<pre><code class="bash">
service mysql start
# then run
/usr/bin/mysql_secure_installation
# setup root password
</code></pre>


<p><strong>Install HHVM</strong></p>


<pre><code class="bash">
# needed to work around libstdc version issue
sudo yum upgrade --setopt=protected_multilib=false --skip-broken

# setup the hop5 repo
cd /etc/yum.repos.d
sudo wget http://www.hop5.in/yum/el6/hop5.repo

# show available versions of hvvm
yum list --showduplicates hhvm

# install latest verison show from list above
yum --nogpgcheck install -y hhvm-3.2.0-1.el6
</code></pre>


<p><strong>Install Nginx and PHP-FPM</strong></p>


<pre><code class="bash">
yum --enablerepo=remi install -y nginx php55w-fpm php55w-common
</code></pre>


<p><strong>Configuring Nginx</strong></p>


<pre><code class="bash">
# rename the default config as its not needed
sudo mv /etc/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf.old

# create a new config
vi /etc/nginx/conf.d/server.conf
</code></pre>




<pre><code>
server {
    server_name mydomainname.com www.mydomainname.com;
    access_log /var/log/nginx/access.log main;
    error_log /var/log/nginx/error.log info;

    # 504 is a PHP timeout and must be static
    # 502 is momentary during a PHP restart and should be treated like maintenance
    # other 50x errors are handled by Magento
    error_page 502 504 /var/www/mysite/504.html;

    listen 80;
    #listen 443 ssl;
 
    # if you are using a load balancer uncomment these lines
    # header from the hardware load balancers
    #real_ip_header X-Forwarded-For;
    # trust this header from anything inside the subnet
    #set_real_ip_from X.X.X.1/24;
    # the header is a comma-separated list; the left-most IP is the end user
    #real_ip_recursive on;

    # ensure zero calls are written to disk
    client_max_body_size          16m;
    client_body_buffer_size       2m;
    client_header_buffer_size     16k;
    large_client_header_buffers   8 8k;

    root /var/www/mysite;
    index index.php;

    fastcgi_read_timeout    90s;
    fastcgi_send_timeout    60s;
    
    # ensure zero calls are written to disk
    fastcgi_buffers 512 16k;
    fastcgi_buffer_size 512k;
    fastcgi_busy_buffers_size 512k;

    # remove the cache-busting timestamp
    location ~* (.+)\.(\d+)\.(js|css|png|jpg|jpeg|gif)$ {
        try_files $uri $1.$3;
        access_log off;
        log_not_found off;
        expires 21d;
        add_header Cache-Control &quot;public&quot;;
    }

    # do not log static files; regexp should capture alternate cache-busting timestamps
    location ~* \.(jpg|jpeg|gif|css|png|js|ico|txt|swf|xml|svg|svgz|mp4|ogg|ogv)(\?[0-9]+)?$ {
        access_log off;
        log_not_found off;
        expires 21d;
        add_header Cache-Control &quot;public&quot;;
    }

    # Server
    include main.conf;
    include security.conf;

}
</code></pre>


<p><strong>Create a home for your website</strong><br />
If you don&#8217;t already have a place for your website files to live you will need to create one:</p>


<pre><code class="bash">
sudo mkdir -p /var/www/mysite/

# while you at it create a nice static error page
echo &quot;error page&quot; &gt;&gt; /var/www/mysite/504.html
</code></pre>


<p><strong>Setup Nginx for HHVM and Magento</strong><br />
Nginx needs to be told how to work with PHP traffic and forward it via FastCGI to HHVM. Here is a good configuration. You will notice their is some standard rewrites for Magento assets in place.</p>


<pre><code class="bash">
vi /etc/nginx/main.conf
</code></pre>




<pre><code>
rewrite_log on;
 
location / {
  index index.php;
  try_files $uri $uri/ @handler;
}
 
location @handler {
  rewrite / /index.php;
}
 
## force www in the URL
if ($host !~* ^www\.) {
  #rewrite / $scheme://www.$host$request_uri permanent;
}
 
## Forward paths like /js/index.php/x.js to relevant handler
location ~ \.php/ {
  rewrite ^(.*\.php)/ $1 last;
}
 
location /media/catalog/ {
  expires 1y;
  log_not_found off;
  access_log off;
}
 
location /skin/ {
  expires 1y;
}
 
location /js/ {
  access_log off;
}

location ~ \.php$ { ## Execute PHP scripts

  if (!-e $request_filename) { rewrite / /index.php last; } ## Catch 404s that try_files miss
  
  expires off; ## Do not cache dynamic content

  # for this tutorial we are going to use a unix socket
  # but if HHVM was running on another host we could forego unix socket
  # in favor of an IP address and port number as follows:
  #fastcgi_pass 127.0.0.1:8080;

  fastcgi_pass unix:/var/run/hhvm/sock;

  fastcgi_index index.php;
  #fastcgi_param HTTPS $fastcgi_https;
  fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

  # if you need to explictly specify a store code for Magento do it here
  # this is useful if you are running multiple stores with different hostnames
  #fastcgi_param MAGE_RUN_CODE default;
  #fastcgi_param MAGE_RUN_TYPE store;

  include fastcgi_params; ## See /etc/nginx/fastcgi_params
 
  fastcgi_keep_conn on; #hhvm param
}
</code></pre>


<p>Next we need to setup our security configuration:</p>


<pre><code class="bash">
vi /etc/nginx/security.conf
</code></pre>




<pre><code>
## General Magento Security
location /app/ { deny all; }
location /includes/ { deny all; }
location /lib/ { deny all; }
location /media/downloadable/ { deny all; }
location /pkginfo/ { deny all; }
location /report/config.xml { deny all; }
location /var/ { deny all; }
 
## Disable .htaccess and other hidden files
location /\. {
  return 404;
}
 
## Disable all methods besides HEAD, GET and POST.
if ($request_method !~ ^(GET|HEAD|POST)$ ) {
  return 444;
}
</code></pre>


<p><strong>HHVM Configuration</strong></p>


<pre><code class="bash">
vi /etc/hhvm/server.hdf
</code></pre>




<pre><code>
PidFile = /var/run/hhvm/pid

Server {
  Port = 8080
  SourceRoot = /var/www/mysite
  DefaultDocument = index.php
}

Log {
  Level = Warning
  AlwaysLogUnhandledExceptions = true
  RuntimeErrorReportingLevel = 8191
  UseLogFile = true
  UseSyslog = false
  File = /var/log/hhvm/error.log
  Access {
    * {
      File = /var/log/hhvm/access.log
      Format = %h %l %u % t \&quot;%r\&quot; %&gt;s %b
    }
  }
}

Repo {
  Central {
    Path = /var/log/hhvm/.hhvm.hhbc
  }
}

#include &quot;/usr/share/hhvm/hdf/static.mime-types.hdf&quot;
StaticFile {
  FilesMatch {
    * {
      pattern = .*\.(dll|exe)
      headers {
        * = Content-Disposition: attachment
      }
    }
  }
  Extensions : StaticMimeTypes
}
MySQL {
  TypedResults = false
}
</code></pre>


<p><strong>HHVM Fast-CGI support</strong><br />
HHVM will need to start with Fast-CGI support so Nginx can forward PHP request to it.  We also need to edit the start up script to make HHVM use a unix socket. To do this edit the following file:</p>


<pre><code class="bash">
vi /etc/init.d/hhvm
</code></pre>


<p>I&#8217;ve only made a few changes to the start function start function to enable zend sorting per [Daniel Sloof](https://github.com/danslo) recommendation. I&#8217;ve also change the shutdown to kill the proper pid file (/var/run/hhvm/hhvm.pid). Here is the full init file:</p>


<pre><code class="bash">
#!/bin/bash
#
#	/etc/rc.d/init.d/hhvm
#
# Starts the hhvm daemon
#
# chkconfig: 345 26 74
# description: HHVM (aka the HipHop Virtual Machine) is an open-source virtual machine designed for executing programs written in Hack and PHP
# processname: hhvm

### BEGIN INIT INFO
# Provides: hhvm
# Required-Start: $local_fs
# Required-Stop: $local_fs
# Default-Start:  2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: start and stop hhvm
# Description: HHVM (aka the HipHop Virtual Machine) is an open-source virtual machine designed for executing programs written in Hack and PHP
### END INIT INFO

# Source function library.
. /etc/init.d/functions

start() {
	echo -n &quot;Starting hhvm: &quot;
        /usr/bin/hhvm --config /etc/hhvm/server.hdf --user apache --mode daemon -vServer.Type=fastcgi -vServer.FileSocket=/var/run/hhvm/sock -vEval.EnableZendSorting=1
	touch /var/lock/subsys/hhvm
}	

stop() {
	echo -n &quot;Shutting down hhvm: &quot;
	killproc -p /var/run/hhvm/pid
	rm -f /var/lock/subsys/hhvm
}

case &quot;$1&quot; in
    start)
	start
	;;
    stop)
	stop
	;;
    status)
    if [ ! -f /var/run/hhvm/pid ]; then
            echo &quot;hhvm not is running&quot;
    else
            echo &quot;hhvm is running&quot;
    fi
    ;;
    restart)
    	stop
	start
	;;
    reload|condrestart|probe)
	echo &quot;$1 - Not supported.&quot;
	;;
    *)
	echo &quot;Usage: hhvm {start|stop|status|reload|restart[|probe]&quot;
	exit 1
	;;
esac
exit $?
</code></pre>


<p><strong>Starting HHVM</strong><br />
As you can see if the init file for HHVM we started it with the user &#8220;apache&#8221;. So before starting HHVM make sure the directory your files are stored is owned by that group. Yes I know I&#8217;m being lazy and probably should create a new user and group running hhvm.</p>


<pre><code class="bash">
sudo chown apache:apache /var/www -R
</code></pre>


<p>We also need to give HHVM the permissions to:</p>


<pre><code class="bash">
mkdir -p /var/run/hhvm
chown apache:apache /var/run/hhvm
chmod 775 /var/run/hhvm
</code></pre>


<p>Finally we can start Nginx PHP-FPM and HHVM.</p>


<pre><code class="bash">
service nginx start
service php-fpm start
service hhvm start
</code></pre>


<p>The famous phpinfo() function will not work on HHVM but there is a very nice HHVM equivalent. Lets download it for fun:</p>


<pre><code class="bash">
cd /var/www/mysite/
wget https://gist.githubusercontent.com/ck-on/67ca91f0310a695ceb65/raw/hhvminfo.php
</code></pre>


<p><strong>HHVM admin</strong><br />
HHVM has an admin tool you can use to get stats &#8211; [AdminServer](http://hhvm.com/blog/521/the-adminserver). If you want to see what is available you can create the following file:</p>


<pre><code class="bash">
vi /etc/nginx/conf.d/admin.conf
</code></pre>




<pre><code>
server {
    # hhvm admin
    listen 8889;

    location ~ {
        fastcgi_pass   127.0.0.1:8888;
        include        fastcgi_params;
    }
}
</code></pre>


<p>Then add this block to your hhvm configuration:</p>


<pre><code class="bash">
vi /etc/hhvm/config.hdf
</code></pre>




<pre><code>
AdminServer {
  Port = 8888
  Password = mySecretPassword
}
</code></pre>


<p><strong>Some additional tuning</strong></p>
<p>It is also recommended to use “pm = static” mode (instead of “pm = dynamic”) if you decide to<br />
dedicate a server for PHP-FPM exclusively, as there is no need for dynamic allocation of resources to PHP-FPM. The “pm” part of the configuration is more or less the same as if you were to configure Apache.</p>


<pre><code class="bash">
vi /etc/php-fpm.d/www.conf

# make these changes
pm = static
pm.max_children = 48
pm.start_servers = 8
pm.min_spare_servers = 8
pm.max_spare_servers = 8
pm.max_requests = 40000
request_terminate_timeout = 120
catch_workers_output = yes
security.limit_extensions = .php .html .phtml
</code></pre>




<pre><code class="bash">
vi /etc/php.ini
</code></pre>




<pre><code>
[PHP]
engine = On
short_open_tag = On
asp_tags = Off
precision = 14
y2k_compliance = On
output_buffering = 4096
zlib.output_compression = Off
implicit_flush = Off
unserialize_callback_func =
serialize_precision = 100
allow_call_time_pass_reference = Off
safe_mode = Off
safe_mode_gid = Off
safe_mode_include_dir =
safe_mode_exec_dir =
safe_mode_allowed_env_vars = PHP_
safe_mode_protected_env_vars = LD_LIBRARY_PATH
disable_functions =
disable_classes =
expose_php = On
max_execution_time = 90
max_input_time = 120
memory_limit = 512M
max_input_vars = 25000
error_reporting = E_ALL &amp; ~E_DEPRECATED
display_errors = Off
display_startup_errors = Off
log_errors = On
log_errors_max_len = 1024
ignore_repeated_errors = Off
ignore_repeated_source = Off
report_memleaks = On
track_errors = Off
html_errors = Off
variables_order = &quot;GPCS&quot;
request_order = &quot;GP&quot;
register_globals = Off
register_long_arrays = Off
register_argc_argv = Off
auto_globals_jit = On
post_max_size = 64M
magic_quotes_gpc = Off
magic_quotes_runtime = Off
magic_quotes_sybase = Off
auto_prepend_file =
auto_append_file =
default_mimetype = &quot;text/html&quot;
doc_root =
user_dir =
enable_dl = Off
file_uploads = On
upload_max_filesize = 64M
allow_url_fopen = On
allow_url_include = Off
default_socket_timeout = 90

realpath_cache_size = 128k
realpath_cache_ttl = 86400


[Pdo_mysql]
pdo_mysql.cache_size = 2000

[Syslog]
define_syslog_variables  = Off

[mail function]
SMTP = localhost
smtp_port = 25
sendmail_path = /usr/sbin/sendmail -t -i
mail.add_x_header = On

[SQL]
sql.safe_mode = Off

[ODBC]
odbc.allow_persistent = On
odbc.check_persistent = On
odbc.max_persistent = -1
odbc.max_links = -1
odbc.defaultlrl = 4096
odbc.defaultbinmode = 1

[MySQL]
mysql.allow_persistent = Off
mysql.max_persistent = -1
mysql.max_links = -1
mysql.default_port =
mysql.default_socket =
mysql.default_host =
mysql.default_user =
mysql.default_password =
mysql.connect_timeout = 60
mysql.trace_mode = Off

[MySQLi]
mysqli.max_links = -1
mysqli.default_port = 3306
mysqli.default_socket =
mysqli.default_host =
mysqli.default_user =
mysqli.default_pw =
mysqli.reconnect = Off

[PostgresSQL]
pgsql.allow_persistent = On
pgsql.auto_reset_persistent = Off
pgsql.max_persistent = -1
pgsql.max_links = -1
pgsql.ignore_notice = 0
pgsql.log_notice = 0

[Sybase-CT]
sybct.allow_persistent = On
sybct.max_persistent = -1
sybct.max_links = -1
sybct.min_server_severity = 10
sybct.min_client_severity = 10

[bcmath]
bcmath.scale = 0

[Session]
session.save_handler = files
session.save_path = &quot;/var/lib/php/session&quot;
session.use_cookies = 1
session.use_only_cookies = 1
session.name = PHPSESSID
session.auto_start = 0
session.cookie_lifetime = 0
session.cookie_path = /
session.cookie_domain =
session.cookie_httponly =
session.serialize_handler = php
session.gc_probability = 1
session.gc_divisor = 1000
session.gc_maxlifetime = 1440
session.bug_compat_42 = Off
session.bug_compat_warn = Off
session.referer_check =
session.entropy_length = 0
session.entropy_file =
session.cache_limiter = nocache
session.cache_expire = 180
session.use_trans_sid = 0
session.hash_function = 0
session.hash_bits_per_character = 5
url_rewriter.tags = &quot;a=href,area=href,frame=src,input=src,form=fakeentry&quot;

[MSSQL]
mssql.allow_persistent = On
mssql.max_persistent = -1
mssql.max_links = -1
mssql.min_error_severity = 10
mssql.min_message_severity = 10
mssql.compatability_mode = Off
mssql.secure_connection = Off

[Tidy]
tidy.clean_output = Off

[soap]
soap.wsdl_cache_enabled=1
soap.wsdl_cache_dir=&quot;/tmp&quot;
soap.wsdl_cache_ttl=86400
</code></pre>


<p><strong>Installing Redis</strong><br />
Since we are going to be using Redis for our store lets make sure to install it.</p>


<pre><code class="bash">
yum install -y gcc
wget http://download.redis.io/redis-stable.tar.gz
tar xvzf redis-stable.tar.gz
cd redis-stable
make
make install

# give Redis a home
mkdir -p /var/redis
</code></pre>


<p><strong>Redis startup scripts</strong><br />
We are going to be running 3 Redis instances for Magento sessions, cache, and FPC. Each redis session is on a different port. To do this we need startup scripts. Here is my startup scripts. As you can see I&#8217;m using unix sockets and allocating 500mb for sessions, 1gb for cache, and 2gb for FPC.</p>
<p><strong>Sessions on port 8302</strong></p>


<pre><code class="bash">
vi /etc/redis/8302.conf
</code></pre>




<pre><code class="bash">
daemonize yes
pidfile /var/run/redis_8302.pid
port 8302
unixsocket /var/run/redis_8302.sock
unixsocketperm 777
timeout 0
tcp-keepalive 0
loglevel notice
logfile /var/log/redis_8302.log
databases 2
save 900 1
save 300 10
save 60 10000
stop-writes-on-bgsave-error yes
rdbcompression no
rdbchecksum yes
dbfilename dump.rdb
dir /var/redis/8302
slave-serve-stale-data yes
slave-read-only yes
repl-disable-tcp-nodelay no
slave-priority 100
maxmemory-policy volatile-lru
maxmemory 500mb
appendonly no
appendfsync everysec
no-appendfsync-on-rewrite no
auto-aof-rewrite-percentage 100
auto-aof-rewrite-min-size 64mb
lua-time-limit 5000
slowlog-log-slower-than 10000
slowlog-max-len 128
hash-max-ziplist-entries 512
hash-max-ziplist-value 64
list-max-ziplist-entries 512
list-max-ziplist-value 64
set-max-intset-entries 512
zset-max-ziplist-entries 128
zset-max-ziplist-value 64
activerehashing yes
client-output-buffer-limit normal 0 0 0
client-output-buffer-limit slave 256mb 64mb 60
client-output-buffer-limit pubsub 32mb 8mb 60
hz 10
aof-rewrite-incremental-fsync yes
</code></pre>


<p><strong>Cache on port 8402</strong></p>


<pre><code class="bash">
vi /etc/redis/8402.conf
</code></pre>




<pre><code class="bash">
daemonize yes
pidfile /var/run/redis_8402.pid
port 8402
unixsocket /var/run/redis_8402.sock
unixsocketperm 777
timeout 0
tcp-keepalive 0
loglevel notice
logfile /var/log/redis_8402.log
databases 2
save 900 1
save 300 10
save 60 10000
stop-writes-on-bgsave-error yes
rdbcompression no
rdbchecksum yes
dbfilename dump.rdb
dir /var/redis/8402
slave-serve-stale-data yes
slave-read-only yes
repl-disable-tcp-nodelay no
slave-priority 100
maxmemory-policy volatile-lru
maxmemory 1gb
appendonly no
appendfsync everysec
no-appendfsync-on-rewrite no
auto-aof-rewrite-percentage 100
auto-aof-rewrite-min-size 64mb
lua-time-limit 5000
slowlog-log-slower-than 10000
slowlog-max-len 128
hash-max-ziplist-entries 512
hash-max-ziplist-value 64
list-max-ziplist-entries 512
list-max-ziplist-value 64
set-max-intset-entries 512
zset-max-ziplist-entries 128
zset-max-ziplist-value 64
activerehashing yes
client-output-buffer-limit normal 0 0 0
client-output-buffer-limit slave 256mb 64mb 60
client-output-buffer-limit pubsub 32mb 8mb 60
hz 10
aof-rewrite-incremental-fsync yes
</code></pre>


<p><strong>FPC Cache on port 8502</strong></p>


<pre><code class="bash">
vi /etc/redis/8502.conf
</code></pre>




<pre><code class="bash">
daemonize yes
pidfile /var/run/redis_8502.pid
unixsocket /var/run/redis_8502.sock
unixsocketperm 777
port 8502
timeout 0
tcp-keepalive 0
loglevel notice
logfile /var/log/redis_8502.log
databases 2
save 900 1
save 300 10
save 60 10000
stop-writes-on-bgsave-error yes
rdbcompression no
rdbchecksum yes
dbfilename dump.rdb
dir /var/redis/8502
slave-serve-stale-data yes
slave-read-only yes
repl-disable-tcp-nodelay no
slave-priority 100
maxmemory-policy volatile-lru
maxmemory 2gb
appendonly no
appendfsync everysec
no-appendfsync-on-rewrite no
auto-aof-rewrite-percentage 100
auto-aof-rewrite-min-size 64mb
lua-time-limit 5000
slowlog-log-slower-than 10000
slowlog-max-len 128
hash-max-ziplist-entries 512
hash-max-ziplist-value 64
list-max-ziplist-entries 512
list-max-ziplist-value 64
set-max-intset-entries 512
zset-max-ziplist-entries 128
zset-max-ziplist-value 64
activerehashing yes
client-output-buffer-limit normal 0 0 0
client-output-buffer-limit slave 256mb 64mb 60
client-output-buffer-limit pubsub 32mb 8mb 60
hz 10
aof-rewrite-incremental-fsync yes
</code></pre>


<p><strong>Redis Startup scripts</strong><br />
We need a way to start our servers. We can do this by creating startup scripts for it. Here are my 3 Redis startup scripts.</p>


<pre><code class="bash">
vi /etc/init.d/redis_8302
</code></pre>




<pre><code class="bash">
#!/bin/sh
#
# redis        Startup script for Redis Server
#
# chkconfig: - 90 10
# description: Redis is an open source, advanced key-value store.
#
# processname: redis-server

REDISPORT=8302
EXEC=/usr/local/bin/redis-server
CLIEXEC=/usr/local/bin/redis-cli

PIDFILE=/var/run/redis_8302.pid
CONF=&quot;/etc/redis/8302.conf&quot;

case &quot;$1&quot; in
    start)
        if [ -f $PIDFILE ]
        then
                echo &quot;$PIDFILE exists, process is already running or crashed&quot;
        else
                echo &quot;Starting Redis server...&quot;
                $EXEC $CONF
        fi
        ;;
    stop)
        if [ ! -f $PIDFILE ]
        then
                echo &quot;$PIDFILE does not exist, process is not running&quot;
        else
                PID=$(cat $PIDFILE)
                echo &quot;Stopping ...&quot;
                $CLIEXEC -p $REDISPORT shutdown
                while [ -x /proc/${PID} ]
                do
                    echo &quot;Waiting for Redis to shutdown ...&quot;
                    sleep 1
                done
                echo &quot;Redis stopped&quot;
        fi
        ;;
    *)
        echo &quot;Please use start or stop as first argument&quot;
        ;;
esac

exit 0
</code></pre>




<pre><code class="bash">
vi /etc/init.d/redis_8402
</code></pre>




<pre><code class="bash">
#!/bin/sh
#
# redis        Startup script for Redis Server
#
# chkconfig: - 90 10
# description: Redis is an open source, advanced key-value store.
#
# processname: redis-server

REDISPORT=8402
EXEC=/usr/local/bin/redis-server
CLIEXEC=/usr/local/bin/redis-cli

PIDFILE=/var/run/redis_8402.pid
CONF=&quot;/etc/redis/8402.conf&quot;

case &quot;$1&quot; in
    start)
        if [ -f $PIDFILE ]
        then
                echo &quot;$PIDFILE exists, process is already running or crashed&quot;
        else
                echo &quot;Starting Redis server...&quot;
                $EXEC $CONF
        fi
        ;;
    stop)
        if [ ! -f $PIDFILE ]
        then
                echo &quot;$PIDFILE does not exist, process is not running&quot;
        else
                PID=$(cat $PIDFILE)
                echo &quot;Stopping ...&quot;
                $CLIEXEC -p $REDISPORT shutdown
                while [ -x /proc/${PID} ]
                do
                    echo &quot;Waiting for Redis to shutdown ...&quot;
                    sleep 1
                done
                echo &quot;Redis stopped&quot;
        fi
        ;;
    *)
        echo &quot;Please use start or stop as first argument&quot;
        ;;
esac

exit 0
</code></pre>




<pre><code class="bash">
vi /etc/init.d/redis_8502
</code></pre>




<pre><code class="bash">
#!/bin/sh
#
# redis        Startup script for Redis Server
#
# chkconfig: - 90 10
# description: Redis is an open source, advanced key-value store.
#
# processname: redis-server

REDISPORT=8502
EXEC=/usr/local/bin/redis-server
CLIEXEC=/usr/local/bin/redis-cli

PIDFILE=/var/run/redis_8502.pid
CONF=&quot;/etc/redis/8502.conf&quot;

case &quot;$1&quot; in
    start)
        if [ -f $PIDFILE ]
        then
                echo &quot;$PIDFILE exists, process is already running or crashed&quot;
        else
                echo &quot;Starting Redis server...&quot;
                $EXEC $CONF
        fi
        ;;
    stop)
        if [ ! -f $PIDFILE ]
        then
                echo &quot;$PIDFILE does not exist, process is not running&quot;
        else
                PID=$(cat $PIDFILE)
                echo &quot;Stopping ...&quot;
                $CLIEXEC -p $REDISPORT shutdown
                while [ -x /proc/${PID} ]
                do
                    echo &quot;Waiting for Redis to shutdown ...&quot;
                    sleep 1
                done
                echo &quot;Redis stopped&quot;
        fi
        ;;
    *)
        echo &quot;Please use start or stop as first argument&quot;
        ;;
esac

exit 0
</code></pre>


<p><strong>Set the file permissions on the startup scripts:</strong></p>


<pre><code class="bash">
cd /etc/init.d/
chmod 755 redis_*

mkdir -p /var/redis/8302
mkdir -p /var/redis/8402
mkdir -p /var/redis/8502
chmod 775 /var/redis/8302
chmod 775 /var/redis/8402
chmod 775 /var/redis/8502
</code></pre>


<p><strong>Starting Redis servers</strong></p>


<pre><code class="bash">
sh /etc/init.d/redis_8302 start
sh /etc/init.d/redis_8402 start
sh /etc/init.d/redis_8502 start
</code></pre>


<p>You can verify it is running by using the redis-cli tool:</p>


<pre><code class="bash">
redis-cli -p 8302
redis-cli -p 8402
redis-cli -p 8502
</code></pre>


<p><strong>Apache JMeter Benchmarking</strong><br />
Magento has release a beta version of performance testing scripts that are available <a href="https://github.com/magento/magento-performance-toolkit">here</a>. I followed the instructions in the accompanying PDF document, but had some troubles when I was trying to run the JMeter script on my local OSX machine. Magento doesn&#8217;t mention it in the documentation but you also need to add the <a href="http://jmeter-plugins.org">JMeter plugins</a>.</p>
<p>When you are ready to run the benchmark simply issue:</p>


<pre><code class="bash">
jmeter -n -t benchmark.jmx -Jhost=beepaux03.mmm.com -Jbase_path=/ -Jusers=100 -Jramp_period=300 -Jreport_save_path=./
</code></pre>


<p>Or you can use the GUI version of JMeter and get the fancy charts and graphs. You just need to enable the charts and set the parameters. I&#8217;m a rookie at JMeter so I&#8217;m sure I have lots to learn.</p>
<p><strong>Here are the OSX instructions for those using homebrew:</strong></p>


<pre><code class="bash">
brew install jmeter
wget http://jmeter-plugins.org/downloads/file/JMeterPlugins-Standard-1.2.0.zip
wget http://jmeter-plugins.org/downloads/file/JMeterPlugins-Extras-1.2.0.zip
unzip JMeterPlugins-Extras-1.2.0 
yes | cp -R JMeterPlugins-Extras-1.2.0/lib /usr/local/Cellar/jmeter/2.11/libexec/lib
yes | cp -R JMeterPlugins-Standard-1.2.0/lib /usr/local/Cellar/jmeter/2.11/libexec/lib
</code></pre>


<p>By default the distribution is as follows:</p>
<ul>
<li>Browsing, adding items to a cart and abandoning the cart: 62%</li>
<li>Just browsing: 30%</li>
<li>Browsing, adding items to a cart and checking out as a guest: 4%</li>
<li>Browsing, adding items to a cart and checking out as a registered customer: 4%.</li>
</ul>
<p>If your interested the inter-workings on the Magento JMeter script there is a detailed break down <a href="http://www.ubik-ingenierie.com/blog/magento-performance-toolkit-and-jmeter-best-practices">here</a>.</p>
<h5>Also to note:</h5>
<p>The JMeter java configuration comes with 512 Mo and very little GC tuning. First ensure you set -Xmx option value to a reasonable value regarding your test requirements. Then change MaxNewSize option in jmeter file to respect the original ratio between MaxNewSize and -Xmx.</p>


<pre><code class="bash">
vi /usr/local/Cellar/jmeter/2.11/libexec/bin/jmeter
# change head param to increase memory
HEAP=&quot;-Xms1G -Xmx3G&quot;
</code></pre>


<p>And now for the results you have been waiting for:</p>
<p><strong>HHVM / Nginx / Redis / Percona </strong><br />
<img src="http://i.imgur.com/4qDNgT6.png"><br />
<br />
<img src="http://i.imgur.com/tp63RXB.png"></p>
<hr />
<p><strong>PHP 5.5.18 / Nginx / Redis / Percona (not using HHVM)</strong><br />
<img src="http://i.imgur.com/5pKnV9Q.png"><br />
<br />
<img src="http://i.imgur.com/34z4T4p.png"></p>
<hr />
<strong>Charted together</strong><br />
<img src="http://i.imgur.com/6641NsX.png"></p>
<p><strong>In conclusion</strong><br />
I&#8217;m still testing HHVM before putting it in production. If you are using HHVM with Magento in production I would love to hear from you. Hit me up on twitter @tegansnyder.</p>

	    
<div id="disqus_thread">
    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/setting-up-hhvm-nginx-php-5-5-percona-5-5-and-redis-for-magento/';
var disqus_identifier = '1036 http://www.tegdesign.com/?p=1036';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Setting up HHVM, Nginx, PHP 5.5, Percona 5.5, and Redis for Magento";
var disqus_config_custom = window.disqus_config;
var disqus_config = function () {
    /*
    All currently supported events:
    onReady: fires when everything is ready,
    onNewComment: fires when a new comment is posted,
    onIdentify: fires when user is authenticated
    */
    
    
    this.language = '';
        this.callbacks.onReady.push(function () {

        // sync comments in the background so we don't block the page
        var script = document.createElement('script');
        script.async = true;
        script.src = '?cf_action=sync_comments&post_id=1036';

        var firstScript = document.getElementsByTagName('script')[0];
        firstScript.parentNode.insertBefore(script, firstScript);
    });
    
    if (disqus_config_custom) {
        disqus_config_custom.call(this);
    }
};

(function() {
    var dsq = document.createElement('script'); dsq.type = 'text/javascript';
    dsq.async = true;
    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();
</script>

	  


</div>

</div>

</div>

@endsection