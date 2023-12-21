RED=\033[31m
GREEN=\033[32m
YELLOW=\033[33m
RESET=\033[0m

install:
	@echo "$(YELLOW)Building containers...$(RESET)"
	@docker-compose up -d --build
	@echo "$(YELLOW)Installing dependencies...$(RESET)"
	@docker-compose exec laminas composer install
	@echo "$(YELLOW)Setting folder permissions...$(RESET)"
	@docker-compose exec laminas chmod 777 -R data/cache
	@echo "$(GREEN)All done!$(RESET)"

start:
	@echo "$(YELLOW)Starting container...$(RESET)"
	@docker-compose up -d

stop:
	@echo "$(YELLOW)Stopping container...$(RESET)"
	@docker-compose stop

status:
	@echo "$(YELLOW)Containers:$(RESET)"
	@docker-compose ps

bash:
	@echo "$(YELLOW)Entering bash...$(RESET)"
	@docker-compose exec laminas bash

restart:
	@echo "$(YELLOW)Restarting container...$(RESET)"
	@docker-compose restart