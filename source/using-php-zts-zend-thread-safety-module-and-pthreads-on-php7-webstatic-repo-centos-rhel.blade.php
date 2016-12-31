<!--
{{ $page_title = 'Using PHP ZTS Zend Thread Safety module and pthreads on PHP7 Webstatic Repo Centos RHEL' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Using PHP ZTS Zend Thread Safety module and pthreads on PHP7 Webstatic Repo Centos RHEL', 'sub_txt' => 'Posted on Nov 17, 2016 at 5:45 pm'])

<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">

<p><strong>BECOM SUDO USER:</strong></p>


<pre><code class="bash">sudo -s</code></pre>



<p><strong>INSTALL WEBSTATIC REPO FOR CENTOS/RED HAT 7:</strong></p>


<pre><code class="bash">rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm</code></pre>



<p><strong>INSTALL PHP WITH COMMON MODULES:</strong></p>


<pre><code class="bash">yum --nogp install -y --enablerepo=webtatic-testing \
php70w php70w-cli php70w-common php70w-devel \
php70w-gd php70w-intl php70w-mbstring php70w-mcrypt \
php70w-mysqlnd php70w-odbc php70w-opcache php70w-pdo \
php70w-pdo_dblib php70w-pear php70w-pgsql php70w-pspell \
php70w-soap php70w-xml php70w-xmlrpc php70w-bcmath</code></pre>



<p><strong>CHANGE TO A TEMP DIRECTORY:</strong></p>


<pre><code class="bash">cd /tmp</code></pre>



<p><strong>PULL DOWN the PTHREADS GIT REPO:</strong></p>


<pre><code class="bash">git clone https://github.com/krakjoe/pthreads.git
cd pthreads
zts-phpize
./configure --with-php-config=/usr/bin/zts-php-config
make</code></pre>



<p><strong>COPY EXTENSION TO PHP-ZTS MODULES FOLDER:</strong></p>


<pre><code class="bash">cp modules/pthreads.so /usr/lib64/php-zts/modules/.</code></pre>



<p><strong>ENABLE EXTENSION IN PHP-ZTS, BY CREATING A FILE:</strong></p>


<pre><code class="bash">vi /etc/php-zts.d/pthreads.ini</code></pre>



<p><strong>ADD THIS TO THE FILE AND SAVE:</strong></p>


<pre><code class="bash">extension=pthreads.so</code></pre>



<p><strong>NEXT CHECK TO SEE IF YOU GOT IT WORKING:</strong></p>


<pre><code class="bash">zts-php -i | grep -i thread</code></pre>



<p><strong>IT SHOULD OUPUT SOMETHING LIKE THIS:</strong></p>


<pre><code class="bash">/etc/php-zts.d/pthreads.ini
Thread Safety => enabled
pthreads</code></pre>



<p><strong>NOW YOU CAN INVOKE PROGRAMS THAT NEED THREADING AND PTHREADS BY USING:</strong></p>


<pre><code class="bash">zts-php (instead of php)</code></pre>



Adapted from: <a href="https://io.ofbeaton.com/2015/02/pthreads-phpzts-rpms-centos/" target="_blank">https://io.ofbeaton.com/2015/02/pthreads-phpzts-rpms-centos/</a>


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