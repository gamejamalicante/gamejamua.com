set :domain, 'v2.gamejamua.com'

role :web, domain
role :app, domain, :primary => true
role :db,  domain, :primary => true