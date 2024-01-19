#!/bin/bash

# Cores para destacar a saída
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para exibir mensagens de sucesso
success() {
  echo -e "${GREEN}$1${NC}"
}

# Função para exibir mensagens informativas
info() {
  echo -e "${YELLOW}$1${NC}"
}

# Cabeçalho
echo "-----------------------------------------------------------------------------"
echo -e "${GREEN}Iniciando banco${NC}"

cd db

# Iniciar containers Docker
docker-compose -p onfly up  -d

cd ../api

# Informar o endereço e a porta do servidor Laravel
info "Iniciando APP e API"

# Iniciar servidor Laravel
# php artisan serve & vue server
php artisan serve

cd ../
# Mensagem de encerramento
success "APP, API e Docker (Banco) inicializados com sucesso!"
