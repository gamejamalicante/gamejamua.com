set :repository, "git@github.com:gamejamalicante/gamejamua.com.git"
set :deploy_to, "/var/www/gamejamua.com"

set :shared_children,     [web_path + "/uploads", app_path + "/logs", web_path + "/blog/app/uploads"]
set :writable_dirs,       ["app/cache", "app/logs", "web/media"]

before 'deploy:create_symlink', 'update_blog'
after 'deploy:create_symlink', 'update_sitemap'