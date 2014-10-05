set :repository, "git@github.com:gamejamalicante/gamejamua.com.git"
set :deploy_to, "/var/www/gamejamua.com"

set :shared_children,     [web_path + "/uploads", app_path + "/logs", web_path + "/blog/app/uploads"]
set :writable_dirs,       ["app/cache", "app/logs", "web/media"]

task :clear_rest_env do
desc 'Clear cache and warmup environment'

    capifony_pretty_print '--> Cleaning rest env'

    run "cd #{release_path} ; php app/console cache:clear --env=rest_prod"

    capifony_puts_ok
end

after 'symfony:cache:warmup', 'clear_rest_env'
before 'deploy:create_symlink', 'update_blog'
after 'deploy:create_symlink', 'update_sitemap'