<!--
{{ $page_title = 'Deploying your Magento code using Capistrano' }}
{{ $page_body_class = 'page-blog-post' }}
-->

@extends('_layouts.master')

@section('body')

@include('_partials.jumbotron', ['main_msg' => 'Deploying your Magento code using Capistrano', 'sub_txt' => 'Posted on August 23, 2014 at 9:02 pm'])



<div class="container">

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-content">


<p>I spend a lot of time lately working on server orchestration type of activities. With the amount of Magento instances I manage and the code bases that make them what they are it&#8217;s very beneficial to me to have a streamlined deployment process. If you have experience running multi-node Magento stores you will agree with me when I say Dev-Ops tools like Ansible and Capistrano are must haves in your tool set. In this post I will describe how to get started using Capistrano for Magento deployments from a private Github repository. This post assumes you are on Red Hat/Fedora/CentOS based linux distro.</p>
<p><strong>Prerequisites:</strong><br />
Note: I&#8217;m using an older version of Capistrano since I haven&#8217;t modified my recipe to work with the latest version and I really have no reason to use the latest version.</p>


<pre><code class="bash">
# if you dont have git installed
sudo yum install git
# if you dont have ruby installed
sudo yum install ruby ruby-devel rubygems
# install capistrano gem
gem install capistrano -v 2.15.5
</code></pre>


<p><strong>Setup your deployment key</strong><br />
In order for Capistrano to be able to connect to your private Github repo you will need to create deployment key. I usually store mine in /usr/share/githubkeys.</p>


<pre><code class="bash">
mkdir /usr/share/githubkeys/
cd /usr/share/githubkeys/
</code></pre>




<pre><code class="bash">
sudo ssh-keygen -t rsa -C
</code></pre>


<p>When it asks you to save the key put:<br />
/usr/share/githubkeys/myRepoName.id_rsa<br />
Enter passphrase (empty for no passphrase): [press enter leave empty]</p>


<pre><code class="bash">
# chmod 400 your keys (very important)
sudo chmod /usr/share/githubkeys/myRepoName.id_rsa
sudo chmod /usr/share/githubkeys/myRepoName.id_rsa.pub
</code></pre>


<p><strong>Add the host mapping for Github:</strong><br />
This trick allows you to have multiple hosts configured that use different private keys. It is especially important if you plan on managing large scale deployments across multiple repositories.</p>


<pre><code class="bash">
sudo vi ~/.ssh/config
# add this to your ~/.ssh/config

Host deploy-myRepoName.github
        Hostname github.com
        Port 22
        User git
        IdentityFile /usr/share/githubkeys/myRepoName.id_rsa
</code></pre>


<p><strong>Add deploy key to Github repo:</strong><br />
Visit your Github repo at github.com and click &#8220;Settings&#8221; -> &#8220;Deploy keys&#8221;</p>


<pre><code class="bash">
cat /usr/share/githubkeys/myRepoName.id_rsa.pub
</code></pre>


<p>Paste in the contents on myRepoName.id_rsa.pub (no line breaks!!)</p>
<p><strong>Test the Github connectivity:</strong><br />
Run the command below, if it succeeds you will see Github complain about how it doesn&#8217;t provide shell access, but the goal is to at least see if you ssh config host file and key work.</p>


<pre><code class="bash">
ssh -vT deploy-myRepoName.github
</code></pre>


<p><strong>Setup Public Key based SSH logins between your App Nodes</strong><br />
While Capistrano can be setup to use SSH usernames and passwords I tend to set it up to use public key authentication. When Capistrano deploys code to each of your app nodes it does it by initiating a SSH connection as the user that ran &#8220;cap&#8221;. So this means in order for public key authentication to work you must have your users public key on each app node. </p>
<p>For the purpose of this tutorial I&#8217;m assuming you will be using the App Node #1 server as the deployment server. Some people setup a dedicated deployment server strictly for deployment.</p>
<p>First check to see if you have a public key on App Node #1:</p>


<pre><code class="bash">
cat ~/.ssh/id_rsa.pub
</code></pre>


<p>If you dont see anything outputted from the above command you haven&#8217;t setup a public key for your user. If you do see something you can skip the next step.</p>
<p>Creating the public key on App Node #1:</p>


<pre><code class="bash">
ssh-keygen -q -t rsa -f ~/.ssh/id_rsa -N &quot;&quot;
cat ~/.ssh/id_rsa.pub &gt; ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
</code></pre>


<p>Creating the public key on App Node #2:</p>


<pre><code class="bash">
ssh-keygen -q -t rsa -f ~/.ssh/id_rsa -N &quot;&quot;
cat ~/.ssh/id_rsa.pub &gt; ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
</code></pre>


<p>Once you have created the public key. Copy the contents of: (App Node #1)</p>


<pre><code class="bash">
cat ~/.ssh/id_rsa.pub
</code></pre>


<p>Paste the contents into the ~/.ssh/authorized_keys file on App Node 2.</p>
<p>You can test the connectivity between App Node #1 and App Node #2 by trying to SSH from App Node #1 to App Node #2 without issuing a password:</p>


<pre><code class="bash">
ssh appnode2
</code></pre>


<p><strong>Setup the deployment folder structure:</strong><br />
In order for Capistrano to work it needs a place to live. I usually create a deploy folder for it. Note this location is not where your code lives, just the Capistrano recipe for deploying code.</p>


<pre><code class="bash">
# run these commands as root
sudo -s
mkdir -p /var/www/deploy/myRepoName
cd /var/www/deploy/myRepoName
capify .
</code></pre>


<p><strong>Setup the deployment recipe</strong><br />
When &#8220;capify .&#8221; above it created a config folder with a deploy.rb file in it. This deploy.rb file is the Capistrano recipe for deploying code. It ships with a default recipe. We are going to modify it to work with our github repo.</p>
<p>Edit the /var/www/deploy/myRepoName/config/deploy.rb to look like this:</p>


<pre><code class="ruby">
default_run_options[:pty] = true

set :application, 'MyStore'
set :deploy_via, :checkout
set :use_sudo, false
set :checkout, &quot;export&quot;
set :scm, &quot;git&quot;

# make sure to set this to the host your added in your ~/.ssh/config
# format is host you added to ~/.ssh/config
# host:[github.com repo name]/repoName.git
set :repository, &quot;deploy-myRepoName.github:myRepoName/myRepoName.git&quot;

# symlinks you need in your deployment
# you obviously don't want to keep your app/etc/local.xml in your repo
# nor do you want to keep your media or var directories
set :app_symlinks, [&quot;/media&quot;, &quot;/var&quot;]
set :app_shared_dirs, [&quot;/app/etc&quot;, &quot;/media&quot;, &quot;/var&quot;]
set :app_shared_files, [&quot;/app/etc/local.xml&quot;]

# define the hostnames to your app nodes
role :web, &quot;appnode1&quot;, &quot;appnode2&quot;

# set variables when deploying your dev site
task :dev do
    set :htaccess_file, &quot;_dev.htaccess&quot;
    set :branch, &quot;develop&quot;
   	set :deploy_to,	&quot;/var/www/sites/mystore-dev&quot;
end

# set variables when deploying your prod site
task :prod do

	puts &quot;\n\e[0;31m   ######################################################################&quot; 
	puts &quot;   #\n   #       Are you REALLY sure you want to deploy to prod?&quot;
	puts &quot;   #\n   #               Enter y/N + enter to continue\n   #&quot;
	puts &quot;   ######################################################################\e[0m\n&quot; 
	proceed = STDIN.gets[0..0] rescue nil 
	exit unless proceed == 'y' || proceed == 'Y' 

	set :htaccess_file, &quot;_prod.htaccess&quot;
            set :branch, &quot;master&quot;
            set :deploy_to,	&quot;/var/www/sites/mystore-prod&quot;
end


# deployment procedures
namespace :deploy do

    task :update do
        transaction do
            update_code
            compass_compile
            symlink
        end
    end

    task :finalize_update do
        transaction do
            run &quot;chmod -R g+w #{releases_path}/#{release_name}&quot;
            run &quot;chmod +x #{releases_path}/#{release_name}/cron.sh&quot;
            run &quot;chown -R :apache #{latest_release}&quot;
        end
    end

    task :symlink do
        transaction do

            if app_symlinks
                # remove the contents of the shared directories
                app_symlinks.each { |link| run &quot;#{try_sudo} rm -rf #{latest_release}#{link}&quot; }
                # add symlinks the directoris in the shared location
                app_symlinks.each { |link| run &quot;ln -nfs #{shared_path}#{link} #{latest_release}#{link}&quot; }
            end

            if app_shared_files
                # remove the contents of the shared directories
                app_shared_files.each { |link| run &quot;#{try_sudo} rm -rf #{latest_release}#{link}&quot; }
                # add symlinks the directoris in the shared location
                app_shared_files.each { |link| run &quot;ln -nfs #{shared_path}#{link} #{latest_release}#{link}&quot; }
            end

            # put your .htaccess file in place
            run &quot;cd #{latest_release} &amp;&amp;  mv #{htaccess_file} .htaccess&quot;

            # update the 'current' link
            run &quot;ln -nfs #{current_release} #{deploy_to}/#{current_dir}&quot;

        end
    end

    task :compass_compile do
        transaction do
            # compile css for your theme
            run &quot;cd #{latest_release} &amp;&amp; compass compile --force skin/frontend/rwd/mytheme/&quot;
        end
    end

end

# run this tasks after you issue deploy:setup
after &quot;deploy:setup&quot;, :magento_shared_dirs

# this sets up all the symlinks for your store
task :magento_shared_dirs, :roles =&gt; :web do

    if app_shared_dirs
        app_shared_dirs.each { |link| run &quot;#{try_sudo} mkdir -p #{shared_path}#{link} &amp;&amp; chmod 777 #{shared_path}#{link}&quot; }
    end

    if app_shared_files
        app_shared_files.each { |link| run &quot;#{try_sudo} touch #{shared_path}#{link} &amp;&amp; chmod 777 #{shared_path}#{link}&quot; }
    end

    # clean up some files
    run &quot;cd #{shared_path} &amp;&amp; rm -rf pids &amp;&amp; rm -rf system &amp;&amp; rm -rf log&quot;

end

# enable maintenance mode across your app nodes
task :disable, :roles =&gt; :web do
    run &quot;cd #{current_path} &amp;&amp; touch maintenance.flag&quot;
end

# disable maintenance mode across your app nodes
task :enable, :roles =&gt; :web do
    run &quot;cd #{current_path} &amp;&amp; rm -f maintenance.flag&quot;
end
</code></pre>


<p><strong>Notes on the deploy.rb</strong><br />
My deploy.rb file contains a task for compiling the SCSS files my theme uses. Magento 1.9/EE 1.14 uses compass to compile them. If you are not using compass or SCSS files in your theme you can remove the task.</p>
<p><strong>Finalize folder structure</strong><br />
Capistrano will setup all the folders and symlinks automatically for you <img src="http://www.tegdesign.com/wp-includes/images/smilies/icon_smile.gif" alt=":)" class="wp-smiley" /></p>


<pre><code class="bash">
# make sure to run this as root
sudo -s
cd /var/www/deploy/myRepoName
cap dev deploy:setup
</code></pre>


<p><strong>Setup Folder permissions</strong><br />
If you are like me you probably have a specific user you want to own your web files. Since you ran the &#8220;cap deploy:setup&#8221; as root you will need to change the ownership of the folders Capistrano created. You don&#8217;t want them being owned by root.</p>


<pre><code class="bash">
sudo chown www:apache /var/www/sites/mystore-dev -R
</code></pre>


<p><strong>Deploy first code using Capistrano</strong><br />
Up until this point we haven&#8217;t actually used Capistrano to deploy code. All we have done is get it setup and the folder structure created. The following commands illustrate how you can deploy code to your app nodes.</p>


<pre><code class="bash">
cd /var/www/deploy/myRepoName

# for the first deploy issue:
cap dev deploy

# for deploying production code issue:
cap prod deploy

# for setting up maintenance page issue:
cap disable
# and to re-enable
cap enable
</code></pre>


<p><strong>Advance Techniques</strong><br />
Sometimes it is handy to test code up to a specific commit_id SHA. Here is an example of deploying the develop branch to a specific commit id.</p>


<pre><code class="bash">
cap -s revision=dca090fa4a24c219cae38ac7cb3c49b7c40823f6 dev deploy
</code></pre>


<p>You can also use variables in your config.rb recipe and pass them via the command line. For example if you want to add a way to enable and disable the compiling of compass you could add a variable to the top of your config.rb like this:</p>


<pre><code class="ruby">
set :compass, &quot;on&quot;
</code></pre>


<p>Then later in your config.rb you could use a switch case like this:</p>


<pre><code class="ruby">
case compass
    when 'on'
    run &quot;cd #{latest_release}/#{modman_folder}skin/frontend/rwd/mytheme &amp;&amp; compass compile --force&quot;
end
</code></pre>


<p>Then you could pass the parameter to your deploy like this:</p>


<pre><code class="bash">
cap dev deploy -s compass=off
</code></pre>




<pre><code class="ruby">
case branch
when 'master'
  run &quot;cd #{latest_release}/#{modman_folder}skin/frontend/rwd/mytheme &amp;&amp; mv config.rb.prod config.rb&quot;
when 'develop'
  run &quot;cd #{latest_release}/#{modman_folder}skin/frontend/rwd/mytheme &amp;&amp; mv config.rb.dev config.rb&quot;
end
</code></pre>


<p>This is just a brief explanation of how I use Capistrano for deployments. Another neat thing you can do is keep a main repo with your Magento core code base and add in additional code using a task for modman. Its pretty handy little trick for decoupling your core code base for maintainability. Imagine a &#8220;cap dev modman update&#8221; command that ran on your app nodes.</p>
<p>Some more resources and information on git and Capistrano can be found in the <a href="http://ruby-doc.org/gems/docs/c/capistrano-edge-2.5.6/Capistrano/Deploy/SCM/Git.html">Ruby Docs here</a>.</p>

	    
<div id="disqus_thread">
            <div id="dsq-content">


            <ul id="dsq-comments">
                    <li class="comment even thread-even depth-1" id="dsq-comment-5685">
        <div id="dsq-comment-header-5685" class="dsq-comment-header">
            <cite id="dsq-cite-5685">
http://sergeif.me/                <span id="dsq-author-user-5685">Sergei Filippov</span>
            </cite>
        </div>
        <div id="dsq-comment-body-5685" class="dsq-comment-body">
            <div id="dsq-comment-message-5685" class="dsq-comment-message"><p>Great write up, very handy! Ansible+Capistrano make a potent mix. Will be using all of this and adding a task for modman and n98-magerun</p>
</div>
        </div>

    </li><!-- #comment-## -->
            </ul>


        </div>

    </div>

<script>
var disqus_url = 'http://www.tegdesign.com/deploying-magento-code-using-capistrano/';
var disqus_identifier = '995 http://www.tegdesign.com/?p=995';
var disqus_container_id = 'disqus_thread';
var disqus_shortname = 'tegdesign';
var disqus_title = "Deploying your Magento code using Capistrano";
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
        script.src = '?cf_action=sync_comments&post_id=995';

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