set :domain, 'beta.gamejamua.com'

role :web, domain
role :app, domain, :primary => true
role :db,  domain, :primary => true

set :deploy_to,   "/home/devel/www/#{domain}"