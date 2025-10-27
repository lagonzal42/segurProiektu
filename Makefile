SHELL=/bin/bash

all:
	@echo "Web irudia sortzen Dockerfiletik"
	@docker build -t="web" .
	@echo "Docker kontainerrak abiarazten"
	@docker-compose up -d
	@echo "Docker kontainerrak abiarazi dira"

stop:
	@docker-compose down
	@echo "Docker kontainerrak gelditu dira"


fclean:
	@echo "Dockerren cacheak eta sareak ezabatuko dira"
	@docker system prune -af

	@echo "Datubasearen balio guztiak borratuko dira eta hasierako sciptak emandakoak jarriko dira"
	@sudo rm -fr mysql
	@echo "Dockerren volumenak ezabatuko dira"
	@if [ -n "$$(docker volume ls -q)" ]; then \
		docker volume rm $$(docker volume ls -q); \
	else \
		echo "Ez dago volumenik ezabatzeko."; \
	fi

rebuild:
	@docker-compose up --build -d

re: stop fclean all