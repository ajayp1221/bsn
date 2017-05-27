#sudo nano /etc/httpd/conf.d/php.conf

require 'mina/multistage'
require 'mina/bundler'
require 'mina/rails'
require 'mina/git'
# require 'mina/rbenv'  # for rbenv support. (http://rbenv.org)
# require 'mina/rvm'    # for rvm support. (http://rvm.io)

# Basic settings:
#   domain       - The hostname to SSH to.
#   deploy_to    - Path to deploy into.
#   repository   - Git repo to clone from. (needed by mina/git)
#   branch       - Branch name to deploy. (needed by mina/git)

#set :domain, '52.24.195.24'
#set :deploy_to, '/var/www/business.zakoopi.com'
#set :repository, 'https://github.com/samiksarkar/bsn_zkp.git'
#set :branch, 'master'
#set :user, 'ubuntu'
#set :identity_file, 'live.pem'


# For system-wide RVM install.
#   set :rvm_path, '/usr/local/rvm/bin/rvm'

# Manually create these paths in shared/ (eg: shared/config/database.yml) in your server.
# They will be linked in the 'deploy:link_shared_paths' step.
set :shared_paths, ['webroot/robots.txt','webroot/rq.txt','config/app.php', 'tmp','.htaccess','webroot/.htaccess','webroot/uploads','webroot/iosPush','webroot/emailTmpls','webroot/tmp','webroot/bower_components']

# Optional settings:
#   set :user, 'foobar'    # Username in the server to SSH to.
#   set :port, '30000'     # SSH port number.
#   set :forward_agent, true     # SSH forward_agent.
set :tstamp, Time.now.to_i.to_s
# This task creates backup of uploads and Sends To S3
task :bkpuploads do
  queue! %[mkdir -p "#{deploy_to}/bkp/uploads"]
  queue! %[zip -r #{deploy_to}/bkp/uploads/#{tstamp}-zkp-upld.zip #{deploy_to}/shared/webroot/uploads]
  queue! %[aws s3 sync #{deploy_to}/bkp/uploads/ s3://zkp-bkp/uploads]
  queue! %[rm "#{deploy_to}/bkp/uploads/*.*"]
end

# This task creates backup of database and Sends To S3
task :dbdump do
#  queue! %[mkdir -p "#{deploy_to}/bkp/db"]
#  queue! %[mysqldump zakoopi_new > #{deploy_to}/bkp/db/#{tstamp}-zkp-db.sql --user='root' --password='ALinux@zakoopi']
#  queue! %[aws s3 sync #{deploy_to}/bkp/db/ s3://zkp-bkp/db]
#  queue! %[rm "#{deploy_to}/bkp/db/*.*"]
end

# This task is the environment that is loaded for most commands, such as
# `mina deploy` or `mina rake`.
task :environment do
  # If you're using rbenv, use this to load the rbenv environment.
  # Be sure to commit your .ruby-version or .rbenv-version to your repository.
  # invoke :'rbenv:load'

  # For those using RVM, use this to load an RVM version@gemset.
  # invoke :'rvm:use[ruby-1.9.3-p125@default]'
end

# Put any custom mkdir's in here for when `mina setup` is ran.
# For Rails apps, we'll make some of the shared paths that are shared between
# all releases.
task :setup => :environment do
  queue! %[mkdir -p "#{deploy_to}/#{shared_path}/tmp/cache/models"]
  queue! %[mkdir -p "#{deploy_to}/#{shared_path}/tmp/cache/persistent"]
  queue! %[mkdir -p "#{deploy_to}/#{shared_path}/tmp/cache/views"]
  queue! %[mkdir -p "#{deploy_to}/#{shared_path}/tmp/sessions"]
  queue! %[mkdir -p "#{deploy_to}/#{shared_path}/tmp/tests"]
  # queue! %[chown -R ec2-user "#{deploy_to}/#{shared_path}/tmp"]
  # queue! %[chmod 777 -R "#{deploy_to}/#{shared_path}/tmp"]
#   
  queue! %[mkdir -p "#{deploy_to}/#{shared_path}/webroot"]
  # queue! %[chmod 777 -R "#{deploy_to}/#{shared_path}/webroot"]
  
  queue! %[mkdir -p "#{deploy_to}/#{shared_path}/webroot/uploads"]
  # queue! %[chmod 777 -R "#{deploy_to}/#{shared_path}/webroot/uploads"]

  queue! %[mkdir -p "#{deploy_to}/#{shared_path}/config"]
  # queue! %[chmod g+rx,u+rwx "#{deploy_to}/#{shared_path}/config"]

  queue! %[touch "#{deploy_to}/#{shared_path}/config/live.php"]
  queue  %[echo "-----> Be sure to edit '#{deploy_to}/#{shared_path}/config/live.php'."]
end

desc "Deploys the current version to the server."
task :deploy => :environment do
  to :before_hook do
    # Put things to run locally before ssh
  end
  deploy do
    # Put things that will set up an empty directory into a fully set-up
    # instance of your project.
    invoke :'git:clone'
    invoke :'deploy:link_shared_paths'
    queue! %[composer install]
    # queue! %[sudo php bin/cake.php migrations migrate]
    # queue! %[chown -R ec2-user "#{deploy_to}/#{current_path}/logs"]
    # queue! %[sudo chmod 777 -R "#{deploy_to}/#{current_path}/logs"]
    # invoke :'rails:db_migrate'
    # invoke :'rails:assets_precompile'
    invoke :'deploy:cleanup'

    to :launch do
      ##queue! %[sudo cp /var/www/live.php #{deploy_to}/#{shared_path}/config/live.php]
      ##queue! %[sudo chmod 777 -R #{deploy_to}/#{current_path}/plugins/AdminLTE/webroot/js_cache]
      queue! %[cd #{deploy_to}/#{current_path}]
      queue! %[composer dumpautoload -o]
      queue! %[sudo php bin/cake.php plugin assets symlink]
      # queue "mkdir -p #{deploy_to}/#{current_path}/tmp/"
      # queue "touch #{deploy_to}/#{current_path}/tmp/restart.txt"
      ##queue "sudo rm /var/www/html/production/shared/tmp/cache/models/*"
      ##queue "sudo rm /var/www/html/production/shared/tmp/cache/persistent/*"
      queue "sudo service apache2 restart"
      queue "sudo service supervisor stop"
      queue "sudo service gearman-job-server restart"
      queue "sudo service supervisor start"
    end
  end
end

# For help in making your deploy script, see the Mina documentation:
#
#  - http://nadarei.co/mina
#  - http://nadarei.co/mina/tasks
#  - http://nadarei.co/mina/settings
#  - http://nadarei.co/mina/helpers

