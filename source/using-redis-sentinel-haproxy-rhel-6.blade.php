<!--
{{ $page_title = 'Using PHP ZTS Zend Thread Safety module and pthreads on PHP7 Webstatic Repo Centos RHEL' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Using Redis + Sentinel HAProxy RHEL 6', 'sub_txt' => 'Posted on May 27, 2016 at 6:45 pm'])

<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">

<p>Here are some of my notes.</p>

<pre><code class="bash">sudo yum install -y mock
sudo useradd -s /sbin/nologin mockbuild
wget http://ftp.redhat.com/pub/redhat/linux/enterprise/6Server/en/os/SRPMS/haproxy-1.5.4-3.el6.src.rpm
sudo mock -r epel-6-x86_64 haproxy-1.5.4-3.el6.src.rpm
cd /var/lib/mock/epel-6-x86_64/result
sudo rpm -ivh haproxy-1.5.4-3.el6.x86_64.rpm
rpm -qa|grep haproxy</code></pre>



<p><strong>config</strong></p>


<pre><code class="bash">sudo vi /etc/haproxy/haproxy.cfg</code></pre>

<p><strong>For redis sentinel haproxy</strong></p>


<pre><code class="bash">global
	daemon
	maxconn 256

defaults
	mode tcp
	timeout connect 4s
	timeout server  30s
	timeout client  30s


frontend http
	bind :8080
	default_backend stats

backend stats
	mode http
	stats enable
	stats uri	 /
	stats refresh 1s
	stats show-legends
	stats admin if TRUE

frontend redis-alpha
	bind *:26379
	default_backend redis-alpha

backend redis-alpha
	mode tcp
	balance first
	option tcp-check
	option tcplog
	tcp-check send AUTH\ YOUR_REDIS_AUTH_PASSWORD_HERE\r\n
	tcp-check send PING\r\n
	tcp-check expect string +PONG
	tcp-check send info\ replication\r\n
	tcp-check expect string role:master
	tcp-check send QUIT\r\n
	tcp-check expect string +OK

	server redis-1 X.10.29.44:6379 maxconn 1024 check inter 1s
	server redis-2 X.10.56.235:6379 maxconn 1024 check inter 1s
	server redis-3 X.10.29.221:6379 maxconn 1024 check inter 1s
	server redis-4 X.10.29.215:6379 maxconn 1024 check inter 1s
	server redis-5 X.10.29.214:6379 maxconn 1024 check inter 1s</code></pre>




<div id="disqus_thread"></div>
<script>

var disqus_config = function () {
this.page.url = 'http://tegdesign.com/using-php-zts-zend-thread-safety-module-and-pthreads-on-php7-webstatic-repo-centos-rhel';
this.page.identifier = 'using-php-zts-zend-thread-safety-module-and-pthreads-on-php7-webstatic-repo-centos-rhel';
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