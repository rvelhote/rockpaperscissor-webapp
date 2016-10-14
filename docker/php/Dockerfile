# MIT License
#
# Copyright (c) 2016 Ricardo Velhote
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.
FROM ubuntu:xenial
MAINTAINER Ricardo Velhote "rvelhote+github@gmail.com"

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update
RUN apt-get -y upgrade
RUN apt-get -y dist-upgrade
RUN apt-get install -y --no-install-recommends php7.0-cli php7.0-fpm php7.0-xml php7.0-mbstring php7.0-bz2 php7.0-zip php-xdebug ca-certificates php7.0-intl php7.0-pgsql ssh

RUN php -r "file_put_contents('symfony', file_get_contents('https://symfony.com/installer'));" && \
    mv symfony /usr/local/bin/symfony && \
    chmod a+x /usr/local/bin/symfony

# Overidden configuration files
COPY conf /

# Create a user to match the GUID of our user
# FIXME Will only work if the machine has a single user. Fix the permissions properly.
RUN useradd -s /bin/bash -m application
WORKDIR "/opt/application"

EXPOSE 9000
ENTRYPOINT ["php-fpm7.0", "--nodaemonize"]
