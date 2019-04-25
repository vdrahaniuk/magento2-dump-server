# The compatibility with the Magento v2.3 or higher.

#Install via composer

> This package will give you a dump server, that collects all your dump call outputs, so that it does not interfere with HTTP / API responses.

## Run from command line in project root
- `composer require vdrahaniuk/magento2-dump-server`

# Usage
## Start the dump server by calling console command:
- ` bin/magento dump-server:start `

![alt text](https://serving.photos.photobox.com/45823755d6ac03bbaea88288b12301042783cb1d67ab6089d5bd385058bda0b5656395a8.jpg)

## You can set the output format to HTML using the --format option:
- ` bin/magento dump-server:start --format=html > dump.html`

![alt text](https://serving.photos.photobox.com/72874016a25ee9a471ab0a353ce22ef80bee4ff0062382f00057d60433896ae6f367bcde.jpg)

## Configure output on frontend  
`Stores -> Configuration -> Dump Server -> General -> Dump on frontend -> Enabled`

![alt text](https://serving.photos.photobox.com/9112628689d84cf6f8727a4ecaef4e4e0501fd5e7216d8249baf65cdec71ea6693df895f.jpg)

