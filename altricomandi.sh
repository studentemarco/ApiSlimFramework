#!/bin/bash

# Directory del progetto
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

sudo apt update
sudo apt install -y php-mysql

cd "$PROJECT_DIR" && composer require slim/slim:"^4"