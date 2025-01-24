# Dockerfile

FROM php:8.2-apache

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Instalar Node.js e npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expor porta
EXPOSE 8000

# Iniciar o Apache
CMD ["apache2-foreground"]
