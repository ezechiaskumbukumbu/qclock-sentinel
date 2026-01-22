FROM docker.io/library/oraclelinux:9-slim

# 1. Sécurité : Utilisateur applicatif
RUN groupadd -g 1001 bankgroup && \
    useradd -u 1001 -g bankgroup -m -s /bin/bash bankuser

# 2. PHASE A : Installation des dépôts et outils de gestion
RUN microdnf update -y && \
    microdnf install -y oracle-epel-release-el9 \
                       oraclelinux-developer-release-el9 \
                       oracle-instantclient-release-el9 \
                       dnf dnf-plugins-core shadow-utils

# 3. PHASE B : Installation PHP & Oracle (Séparée pour garantir la visibilité des repos)
# On utilise DNF explicitement ici
RUN dnf clean all && \
    dnf makecache && \
    dnf module reset php -y && \
    dnf module enable php:8.1 -y && \
    dnf install -y \
        httpd \
        php \
        php-fpm \
        php-pdo \
        php-process \
        php-devel \
        php-pear \
        gcc \
        oracle-instantclient-basic \
        php-pecl-oci8 \
        supervisor \
        curl && \
    dnf clean all && \
    rm -rf /var/cache/dnf

# 4. Répertoires & Permissions
RUN mkdir -p /run/php-fpm /var/run/httpd /var/log/supervisor /var/www/html && \
    chown -R bankuser:bankgroup /run/php-fpm /var/log/httpd /var/log/supervisor /var/www/html

# 5. Configuration Apache (Socket PHP-FPM)
RUN echo -e "<FilesMatch \\.php$>\nSetHandler \"proxy:unix:/run/php-fpm/www.sock|fcgi://localhost\"\n</FilesMatch>" > /etc/httpd/conf.d/php-fpm.conf

# 6. Fix PHP-FPM (Socket permissions & User context)
RUN sed -i 's/listen = 127.0.0.1:9000/listen = \/run\/php-fpm\/www.sock/g' /etc/php-fpm.d/www.conf && \
    sed -i 's/;listen.owner = nobody/listen.owner = bankuser/g' /etc/php-fpm.d/www.conf && \
    sed -i 's/;listen.group = nobody/listen.group = bankgroup/g' /etc/php-fpm.d/www.conf && \
    sed -i 's/user = apache/user = bankuser/g' /etc/php-fpm.d/www.conf && \
    sed -i 's/group = apache/group = bankgroup/g' /etc/php-fpm.d/www.conf

# 7. Oracle Environment (Important pour le runtime)
ENV LD_LIBRARY_PATH=/usr/lib/oracle/21/client64/lib:$LD_LIBRARY_PATH

WORKDIR /var/www/html

# 8. Code & Supervisor
COPY --chown=bankuser:bankgroup core/src/ .
COPY --chown=bankuser:bankgroup core/build/conf/supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]