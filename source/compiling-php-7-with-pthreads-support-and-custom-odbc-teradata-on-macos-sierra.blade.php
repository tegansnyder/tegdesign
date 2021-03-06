<!--
{{ $page_title = 'Compiling PHP 7 on MacOS Sierra with Threading and Custom ODBC Support' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Compiling PHP 7 on MacOS Sierra with Threading and Custom ODBC Support', 'sub_txt' => 'Posted on December 27, 2016 at 7:14 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">

<p>
I recently got a new Macbook Pro and wanted to document how I setup my PHP environment. I like full control of how PHP is built and I usually build it from source. I do this because I often add custom extensions and modules not found in the common PHP OSX installers. If your looking for a easier method than building from source try <a href="https://php-osx.liip.ch/" target="_blank">php-osx</a>
</p>

<blockquote>
<strong>NOTE:</strong> This post assumes you are running a fresh install of MacOS Sierra 10.12.2 with System Integrity Protection disabled. If you don't know how to disable it just boot into recovery mode and open a terminal and type <em>csrutil disable</em>, or google search it :) This post also assumes you are using Zsh instead of Bash shell. If you are using Bash you can replace anytime you see <em>~/.zshrc</em> with </em>~/.bashrc</em>.
</blockquote>

<p>First lets get some of the prerequisites. Start by grabbing the command line tools neccessary:</p>

<pre><code class="bash">xcode-select --install</code></pre>


<p><strong>Next install homebrew:</strong></p>


<pre><code class="bash">/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"</code></pre>


<p><strong>Then install some generic libraries needed:</strong></p>

<pre><code class="bash">brew install wget
brew install openssl
brew install libxml2
brew link libxml2 --force
brew install jpeg
brew install libpng
brew install libmcrypt</code></pre>


<p>I like to setup a location for stuff I compile from source. I good place to put stuff is `/usr/local/src`. Here is how to setup that location:</p>

<pre><code class="bash">sudo mkdir -p /usr/local/src
sudo chown $(whoami):staff /usr/local/src
cd /usr/local/src</code></pre>


<p>There is an issue with linking the PHP compilation process to the built-in OSX Apache. In order to prevent an error during complication you need to do the following:</p>

<pre><code class="bash">brew install apr apr-util

sudo mkdir -p /Applications/Xcode.app/Contents/Developer/Toolchains/OSX10.12.xctoolchain/usr/local/bin/
sudo ln -s /usr/local/opt/apr/bin/apr-1-config /Applications/Xcode.app/Contents/Developer/Toolchains/OSX10.12.xctoolchain/usr/local/bin/
sudo ln -s /usr/local/opt/apr-util/bin/apu-1-config /Applications/Xcode.app/Contents/Developer/Toolchains/OSX10.12.xctoolchain/usr/local/bin/</code></pre>


<p><strong>Get OpenSSL from source:</strong></p>


<pre><code class="bash">cd /usr/local/src
wget https://www.openssl.org/source/openssl-1.1.0c.tar.gz
tar xzvf openssl-1.1.0c.tar.gz
cd openssl-1.1.0c
./configure shared darwin64-x86_64-cc
make depend
make -j4
sudo make install</code></pre>


<p><strong>Grab ICU4C which is needed ICU 58 for php-intl package:</strong></p>


<pre><code class="bash">cd /usr/local/src
wget http://download.icu-project.org/files/icu4c/58.2/icu4c-58_2-src.tgz
tar xzvf icu4c-58_2-src.tgz
cd icu/source
./runConfigureICU MacOSX
make -j4
sudo make install</code></pre>



<p><strong>Download the PHP source code:</strong></p>


<pre><code class="bash">cd /usr/local/src
wget http://php.net/distributions/php-7.1.0.tar.gz</code></pre>


<p><strong>Extract the archive and move into the folder:</strong></p>


<pre><code class="bash">tar -xzvf php-7.1.0.tar.gz
rm php-7.1.0.tar.gz</code></pre>

<hr>

<h2>Optional Steps for ODBC</h2>

<p>If you are interested in compiling custom ODBC support into PHP so it can connect to a Teradata data warehouse you can do the following steps.  If you will not be using PHP to connect to an Teradata ODBC data source you can skip this step entirely and go to the next part of configuring PHP.</p>

<p>First you need to <a href="http://support.apple.com/kb/DL895" target="_blank">download the Apple ODBC Admin Tool</a>, after installing you can then download the the Teradata ODBC Driver for Mac OS X which can be found <a href="https://downloads.teradata.com/download/connectivity/teradata-odbc-driver-for-mac-os-x" target="_blank">here</a>.

<p><strong>Install system odbc drivers:</strong></p>


<pre><code class="bash">brew install unixodbc</code></pre>


<p>Next setup a symlink to make the path to Teradata more saine:</p>


<pre><code class="bash">sudo ln -s /Library/Application\ Support/teradata /opt/teradata</code></pre>


<p>Setup some paths:</p>

<pre><code class="bash">vi ~/.zshrc</code></pre>

<p>Add at the bottom:</p>

<pre><code class="bash">export ODBC_HOME=/opt/teradata/client/16.00
export ODBCINI=$ODBC_HOME/odbc/odbc.ini
export CPPFLAGS="-I$ODBC_HOME/include"
export CUSTOM_ODBC_LIBS="-L$ODBC_HOME/lib -lodbc -lodbcinst"
export LD_LIBRARY_PATH=$ODBC_HOME/lib</code></pre>

<p>Create a odbc.ini configuration file:</p>


<pre><code class="bash">sudo vi $ODBCINI</code></pre>

<p>Here is an example of the contets:</p>

<pre><code>[ODBC]
Trace=0
TraceFile=/tmp/dmtrace.log

[ODBC Data Sources]
gedw=tdata.dylib

[MY_DSN_NAME_HERE]
Driver=/Library/Application Support/teradata/client/16.00/lib/tdata.dylib
Description=My Data Warehouse
DBCName=MY_SERVER_IP
LastUser=
Username=my_user_name
Password=my_pass_word
Database=
DefaultDatabase=</code></pre>


<hr>

<h2>Configuring PHP</h2>

<p>Now for the fun part. Let's get started by running a `./configure` in the PHP source directory. The options I have below are good options that I use that allow PHP Threading support. If you have never tried threading in PHP using <a href="https://github.com/krakjoe/pthreads" target="_blank">pthreads</a> you are missing out :) </p>

<p>If you skiped out on the ODBC connection above. I assume most of you did. You can omitt the `--with-custom-odbc` line in the configure statement below.</p>

<p><strong>Lets get started:</strong></p>


<pre><code class="bash">cd /usr/local/src
cd php-7.1.0

./configure \
  --prefix=/usr/local/dev/php-7.1.0 \
  --with-config-file-path=/usr/local/dev/php-7.1.0/etc \
  --with-config-file-scan-dir=/usr/local/php-7.1.0/ext \
  --enable-bcmath \
  --enable-cli \
  --enable-mbstring \
  --enable-gd-native-ttf \
  --enable-gd-jis-conv \
  --enable-sockets \
  --enable-exif \
  --enable-ftp \
  --enable-intl \
  --enable-soap \
  --enable-zip \
  --enable-opcache \
  --enable-simplexml \
  --enable-maintainer-zts \
  --with-sqlite3 \
  --enable-xmlreader \
  --enable-xmlwriter \
  --with-mysql-sock=/tmp/mysql.sock \
  --with-mysqli=mysqlnd \
  --with-pdo-mysql=mysqlnd \
  --with-pdo-sqlite \
  --with-bz2 \
  --with-curl \
  --with-gd \
  --with-imap-ssl \
  --with-pear \
  --with-libxml-dir=/Applications/Xcode.app/Contents/Developer/Platforms/MacOSX.platform/Developer/SDKs/MacOSX10.12.sdk/usr/ \
  --with-openssl=/usr/local/Cellar/openssl/1.0.2j \
  --with-xmlrpc \
  --with-xsl \
  --with-mcrypt=/usr/local/bin \
  --with-zlib \
  --with-apxs2 \
  --with-iconv=/usr \
  --with-custom-odbc=$ODBC_HOME</code></pre>


<p><strong>Tell the compiler where it can find openssl:</strong></p>

<pre><code class="bash">export LDFLAGS=-L/usr/local/opt/openssl/lib
export CPPFLAGS=-I/usr/local/opt/openssl/include</code></pre>


<p><strong>Now lets compile PHP. Make sure to run tests too.</strong></p>

<pre><code class="bash">make -j4
make test
sudo make install</code></pre>


<p>After it is compiled I like to setup a directory symlink, this helpful, so whenever a new PHP version comes out I can easily run the same steps above but just change the symlink directory to it's location.</p>

<pre><code class="bash"># create a symlink
ln -s /usr/local/dev/php-7.1.0 /usr/local/php</code></pre>


<p>PHP ships with a configuration template for development. I typically tweak the `memory_limit` and a few other settings, but for now lets just copy it over to the directory you defined as configuration:</p>


<pre><code class="bash">sudo cp /usr/local/src/php-7.1.0/php.ini-development /usr/local/php/etc/php.ini
/usr/local/php/bin/pecl config-set php_ini /usr/local/php/etc/php.ini
/usr/local/php/bin/pear config-set php_ini /usr/local/php/etc/php.ini</code></pre>


<p>Now we need to tell our system where to find our new PHP version. To do this lets add to our PATH.</p>


<pre><code class="bash">vi ~/.zshrc</code></pre>


<p><strong>Add this to the bottom and save:</strong></p>

<pre><code class="bash">export PATH=/usr/local/php/bin:$PATH
export MANPATH=/usr/local/php/php/man:$MANPATH</code></pre>

<pre><code class="bash">source ~./zshrc</code></pre>


<p>Now lets ensure the system Apache is using the correct PHP module. Check the /etc/apache/httpd.conf find the following line:</p>

<pre><code>LoadModule php5_module libexec/apache2/libphp5.so</code></pre>

<p>And comment it out, as we're no longer use php 5, it should look like this:

<pre><code>LoadModule php7_module libexec/apache2/libphp7.so</code></pre>


<p>Next lets give Apache the proper instructions when it encourates a PHP file type. To do this create a new file:</p>

<pre><code class="bash">sudo vi /etc/apache2/other/php7.conf</code></pre>

<p><strong>Add this to the file and save:</strong></p>

<pre><code>AddType application/x-httpd-php .php
DirectoryIndex index.html index.php
</code></pre>


<p>Finally restart Apache and you should be good to go:</p>

<pre><code class="bash">sudo apachectl restart</code></pre>



<div id="disqus_thread"></div>
<script>

var disqus_config = function () {
this.page.url = 'http://tegdesign.com/compiling-php-7-with-pthreads-support-and-custom-odbc-teradata-on-macos-sierra';
this.page.identifier = 'compiling-php-7-with-pthreads-support-and-custom-odbc-teradata-on-macos-sierra';
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