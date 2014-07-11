set :stages,        %w(prod stag)
set :default_stage, "stag"
set :stage_dir,     "app/config"
require 'capistrano/ext/multistage'

set :application, "gamejamua.com"
set :app_path,    "app"
set :user, "devel"

default_run_options[:pty] = true
default_run_options[:shell] = '/bin/bash'
set :ssh_options, { :forward_agent => true }

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,     [web_path + "/uploads", web_path + "/media", app_path + "/uploads", app_path + "/logs", web_path + "/blog/app/uploads"]

set :repository,  "git@github.com:gamejamalicante/gamejamua.com.git"
set :scm,         :git

set :keep_releases, 5
set :deploy_via, :remote_cache
set :use_sudo, false
set :copy_vendors, true

set :model_manager, "doctrine"
set :use_composer, true

set  :keep_releases,  3

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

set :composer_bin, "composer"
set :dump_assetic_assets, true
set :assets_install, true

task :upload_parameters do
desc 'Upload stage parameters'

    capifony_pretty_print '--> Uploading stage parameters'

    origin_file = "app/config/parameters_#{fetch(:stage)}.yml"
    destination_file = deploy_to + '/' + shared_dir + '/app/config/parameters.yml'

    run "sh -c 'if [ ! -d #{File.dirname(destination_file)} ] ; then mkdir -p #{File.dirname(destination_file)}; fi'"
    top.upload(origin_file, destination_file)

    capifony_puts_ok
end

task :update_sitemap do
desc 'Update sitemap'

    capifony_pretty_print '--> Updating site sitemap'

    run "php #{current_path}/app/console presta:sitemap:dump"

    capifony_puts_ok
end

task :update_blog do
desc 'Update blog'

    capifony_pretty_print '--> Updating WordPress blog'

    origin_file = "web/blog/.env.#{fetch(:stage)}"
    destination_file = deploy_to + '/' + shared_dir + '/.env'

    run "sh -c 'if [ ! -d #{File.dirname(destination_file)} ] ; then mkdir -p #{File.dirname(destination_file)}; fi'"
    top.upload(origin_file, destination_file)

    run "mv #{shared_path}/.env #{release_path}/web/blog/ ; cd #{release_path}/web/blog/ ; composer install"

    capifony_puts_ok
end

before 'deploy:create_symlink', 'update_blog'
after 'deploy:create_symlink', 'update_sitemap'
after 'deploy:setup', 'upload_parameters'
before 'deploy', 'upload_parameters'
before 'deploy:share_childs', 'upload_parameters'

set :webserver_user,      "www-data"
set :permission_method,   :acl
set :use_set_permissions, true