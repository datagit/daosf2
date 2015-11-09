ApiSf2
======

A Symfony project created on November 3, 2015, 10:11 am.
http://welcometothebundle.com/symfony2-rest-api-the-best-2013-way/

#for debug
php composer.phar require "elao/web-profiler-extra-bundle" "~2.3@dev"
php composer.phar require "doctrine/doctrine-fixtures-bundle" "dev-master"
php composer.phar require "raulfraile/ladybug-bundle" "~1.0"
php composer.phar require "fzaninotto/faker" "1.4"
# ed api bundles

# st api bundles
php composer.phar require "friendsofsymfony/rest-bundle" "1.7.2"

php composer.phar require "jms/serializer-bundle" "1.0.0"

php composer.phar require "nelmio/api-doc-bundle" "2.10.3"

# note can not install
php composer.phar require "liip/hello-bundle" "dev-master"

#slug
php composer.phar require "cocur/slugify" "dev-master"

#resize image
php composer.phar require "liip/imagine-bundle" "dev-master"

#
php composer.phar require "stof/doctrine-extensions-bundle" "~1.1@dev"

#upload file
php composer.phar require "vich/uploader-bundle" "^0.14.0"

#userbundle
php composer.phar require "friendsofsymfony/user-bundle" "~2.0@dev"

#call api
php composer.phar require "leaseweb/api-caller-bundle" "*"


php composer.phar require "javiereguiluz/easyadmin-bundle" "~1.0"


http://symfony.com/doc/master/bundles/FOSRestBundle/index.html
http://symfony.com/doc/current/bundles/FOSRestBundle/7-manual-route-definition.html
https://github.com/nelmio/NelmioApiDocBundle/blob/master/Resources/doc/index.md


EasyAdminBundle: http://level7systems.co.uk/en/symfony2-admin-panel-in-30-seconds/
https://github.com/javiereguiluz/EasyAdminBundle/blob/master/Resources/doc/getting-started/4-views-and-actions.md
st EasyAdminBundle--------------------
php composer.phar require "javiereguiluz/easyadmin-bundle" "~1.0"
php composer.phar require "friendsofsymfony/user-bundle" "~2.0@dev"
php composer.phar require "stof/doctrine-extensions-bundle" "~1.1@dev"
php composer.phar require "vich/uploader-bundle" "^0.14.0"
php composer.phar require "cocur/slugify" "dev-master"
php composer.phar require "liip/imagine-bundle" "dev-master"
php composer.phar require "doctrine/doctrine-fixtures-bundle" "dev-master"

required configuration
framework:
    translator: { fallback: "%locale%" }

php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console generate:doctrine:entities Dao/DataSourceBundle/Entity
php app/console doctrine:schema:update --force

php app/console fos:user:create admin admin@level7systems.co.uk admin
php app/console fos:user:promote admin ROLE_ADMIN

php app/console assets:install --symlink

php app/console doctrine:fixtures:load
ed EasyAdminBundle--------------------
