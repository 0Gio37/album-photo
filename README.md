#install symfony :
symfony new "project" --full

#install maker de symfony (dans le dossier du projet)
symfony composer req maker --dev

#config connexion BDD
create file 'env.local' (copy from 'file env.')
DATABASE_URL

#install Tailwind css
1/ composer require symfony/webpack-encore-bundle
2/ npm install
3/ npm install -D tailwindcss postcss-loader purgecss-webpack-plugin glob-all path autoprefixer
(https://yoandev.co/utiliser-tailwind-css-2-purgecss-avec-symfony-et-webpack-encore)

#install easyAdmin :
composer require easycorp/easyadmin-bundle

#Format date in twig :
1/ composer require twig/intl-extra
2/ composer require twig/extra-bundle

#manage error pages
composer require symfony/twig-pack