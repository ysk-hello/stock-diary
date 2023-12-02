FROM php:8.2-apache

# コンテナ内の作業フォルダ
WORKDIR /var/www

# コンテナ内でコマンド実行
RUN apt-get update
RUN apt-get install -y locales git procps
RUN apt-get install -y libonig-dev
RUN apt-get install -y nano
RUN apt-get install -y systemctl
RUN apt-get install -y cron
RUN apt-get install -y zip unzip
RUN docker-php-ext-install pdo_mysql mysqli mbstring

# dotnet
# https://learn.microsoft.com/ja-jp/dotnet/iot/deployment
RUN curl -sSL https://dot.net/v1/dotnet-install.sh | bash /dev/stdin --channel STS
RUN echo 'export DOTNET_ROOT=$HOME/.dotnet' >> ~/.bashrc
RUN echo 'export PATH=$PATH:$HOME/.dotnet' >> ~/.bashrc
RUN mv /bin/sh /bin/sh_tmp && ln -s /bin/bash /bin/sh
RUN source ~/.bashrc
RUN rm /bin/sh && mv /bin/sh_tmp /bin/sh

# ロケール
RUN locale-gen ja_JP.UTF-8
RUN localedef -f UTF-8 -i ja_JP ja_JP

# 環境変数
ENV LANG=ja_JP.UTF-8
ENV TZ=Asia/Tokyo

# cron
# TZ変更
# https://qiita.com/jerrywdlee/items/d4468f076bdea236bf3b
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# node.js
# laravel/uiに必要
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - &&\
apt-get install -y nodejs

# SSL有効化
RUN a2enmod ssl
RUN a2enmod rewrite

# SSL関連の設定ファイルをコピー
COPY /conf/000-default.conf /etc/apache2/sites-available
COPY /conf/default-ssl.conf /etc/apache2/sites-available
RUN a2ensite default-ssl

# ファイルをコピー
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY /conf/apache2.conf /etc/apache2

