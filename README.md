#ElasticsearchExplorerModule
Elasticsearch status and search functionality as a Zend Framework 3 module. This module uses the official elasticsearch-php client and the front-end framework Bootstrap.

##Installation
Add the following snippet to your local projects composer.json file:
```
{
  "require": {
    "dan-lyn/elasticsearch-explorer-module": "dev-master"
  },
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/dan-lyn/ElasticsearchExplorerModule.git"
    }
  ]
}
```

Add the module to your local config/application.config.php:
```
'modules' => array(
  // ...
  'ElasticsearchExplorer'
),
```

Create a symlink of the public directory in this module in the applications public directory named 'elasticsearch-explorer-public'. Example:
```
ln -s /var/www/ElasticsearchExplorer/public/ /var/www/zf/public/elasticsearch-explorer-public
```

##Testing
Run PHPUnit from ROOT_DIRECTORY/test/ of this module.

##License
ElasticsearchExplorerModule is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
