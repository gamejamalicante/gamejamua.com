set :domain, 'lon-do-01.albertofem.com'

role :web, domain
role :app, domain, :primary => true
role :db,  domain, :primary => true

set :deploy_to,   "/var/www/gamejamua.com"