# Use the official PHP 8 Apache image
# FROM shinsenter/codeigniter4:latest
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    libonig-dev \
    libtidy-dev \
    libxml2-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    libssl-dev 

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql mysqli mbstring zip intl xml curl

#====================================================================#
#                        INSTALL DB CLIENT                           #
#====================================================================#
RUN apt-get -y install --fix-missing --no-install-recommends \
	mariadb-client

# Enable Apache mod_rewrite
RUN a2enmod rewrite
# Enable Apache mod_headers
RUN a2enmod headers proxy_http
# Enable Apache mod_ssl
RUN a2enmod ssl
# Enable Apache mapserver
RUN	a2enmod cgi


# Set working directory
WORKDIR /var/www/html

#====================================================================#
#                           APACHE CONF                              #
#====================================================================#
COPY ./000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./apache2.conf /etc/apache2/apache2.conf

#====================================================================#
# PHP Ini

RUN echo 'extension=curl' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=ftp' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=fileinfo' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=gd' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=intl' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=ldap' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=mbstring' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=exif' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=mysqli' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=oci8_19' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=openssl' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=pdo_mysql' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=pdo_oci' >> /usr/local/etc/php/php.ini-production
RUN echo 'extension=xsl' >> /usr/local/etc/php/php.ini-production


# Copy application source

COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

#====================================================================#
#                            CLEAN SYSTEM                            #
#====================================================================#
RUN apt-get clean && rm -r /var/lib/apt/lists/* \
	&&  rm -rf \
		/tmp/* \
		/root/.cache
        
ENV CI_ENVIRONMENT='production'
ENV CI_BASE_URL='http://hmdcc-lap3020/'
ENV DB_DEFAULT_GROUP='default'

ENV MYSQL_HOST='mysql'
ENV MYSQL_DATABASE='laravel'
ENV MYSQL_USERNAME='root'
ENV MYSQL_PASSWORD=''


# Expose port 80 and 443
EXPOSE 80
EXPOSE 443

# Healthcheck for Kubernetes
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s CMD curl -f http://localhost/ || exit 1