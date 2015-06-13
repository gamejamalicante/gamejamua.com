.PHONY: cs-fix

cs-fix:
	./vendor/bin/php-cs-fixer fix . --config=sf23 -vvv

provide-prod:
		ansible-playbook -udevel -i vendor/trivialsense/server-infrastructure/inventories/albertofem vendor/trivialsense/server-infrastructure/playbooks/albertofem/http-001.yml -v --ask-sudo-pass --tags gamejamua

deploy-prod:
		cap prod deploy

