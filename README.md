# Gallinago SimpleProductToConfigurableForward #

A module for handling forwarding requests to simple products which are a part of a configurable product
to the configurable product view with a correct version chosen

### Features

* Redirects requests for simple products to configurable product with preselected
attributes
* Adds `View product` button to product edit page which displays the product
in a new tab

### Prerequisites

* Magento >=2.2
* PHP >=7.1

### Installing

##### Using composer (suggested)

1. Add to ``composer.json``
```
"repositories": [
    `{
        "type": "vcs",
        "url": "git@github.com:gallinago/magento2-simple-to-configurable-forward"
    }
],`
```

```
composer require gallinago/module-simple-to-configurable-forward
```

#### Install the module

Run this command
```
bin/magento module:enable Gallinago_SimpleProductToConfigurableForward
bin/magento setup:upgrade
```

### Usage

If you want to display the product in a store view with a code other than `default`
add this directive to your `adminhtml/di.xml`

```
<type name="Gallinago\SimpleProductToConfigurableForward\Block\Adminhtml\Product\Edit\Button\FrontendLinkButton">
    <arguments>
        <argument name="storeCode" xsi:type="string">STORE_CODE</argument>
    </arguments>
</type>
```

### Caveats

Yeah, it overwrites product view controller


### Authors

* **Maciej Sławik** - *Initial work* - [Gallinago](https://github.com/gallinago)

See also the list of [contributors](https://github.com/gallinago/magento2-production-utils/contributors) who participated in this project.

### License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details