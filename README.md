Twendy
======

(Tw)ig in Z(end) Framework is trend(y)!

Twendy is still somewhat experimental and should therefore be regarded as unstable.

About
-----

The Zend Framework and Twig template engine are both immensely powerful.  While it is relatively trivial to use Twig in place of Zend_View for the purpose of rendering view scripts in a Zend Framework application, doing so comes at a cost.  Most significantly, Zend_View helper functionality is lost.  Twendy provides integration between Zend Framework and Twig without sacrificing the usefulness of Zend_View helpers.

Installation
------------

To use Twendy in your Zend Framework application, download Twendy and place it in the library/ directory of your application.  If you don't have it yet, download Twig to the library/ directory too.

Twendy includes a Zend_Application_Resource so using Twendy in your application is as simple as registering the resource with Zend_Application by adding a line to your application.ini configuration file.

`pluginpaths.Twendy_Resource = "Twendy/Application/Resource"`

Configuration
-------------

You can configure Twig from within your application.ini configuration file since the options are used to build the $options array passed into the Twig_Environment constructor.  To enable template caching in Twig for example, usually done by passing the cache directory in $options['cache'] to Twig_Environment, simply add the following line to your application.ini configuration file.

`resources.Twendy_Application_Resource_Twendy.cache = "/path/to/cache"`

Twendy provides some additional configuration options.  These are:

* `extensions[]` - Extension classes to be automatically registered with the Twig_Environment
* `layout` - Parent layout script to extend in child templates
* `layoutPath` - Path to parent layout template
* `viewSuffix` - The template file suffix, uses `tpl` by default

Twendy Twig Extension
---------------------

Twendy includes an optional Twig extension which you can enable by adding the following line to your application.ini configuration file.

`resources.Twendy_Application_Resource_Twendy.extensions[] = "Twendy_Extension"`

The extension currently only does one thing.  It will register the `var_export` function as a Twig filter so as it can be used in templates.  This is included for convenience since the default Zend Framework ErrorController uses it.

The extension isn't required in order to use Twendy and you can register your own extensions in the same way if you prefer.

Twendy Layouts
--------------

Zend_Layout is unnecessary when using Twig as the built-in template inheritance provides native layout support, although the usage is a little different.  Twendy provides a few additional functions which allow you to harness the power of Twig template inheritance but structure your application as you might with Zend_Layout.

Using the layout and layoutPath configuration options above, you can tell Twendy which layout script to use and where it resides.  In your child templates, call the `zend.getLayoutScript` helper function to get the name of the parent template script and extend it as you would normally in Twig (more information on the `zend` template variable follows below).

`{% extends zend.getLayoutScript %}`

You could use a variable here in the same way, but this approach allows you to store your layout scripts in a separate directory if you prefer.

You can swap the `layout` or `layoutPath` at any time by calling the `setLayout()` and `setLayoutPath()` methods respectively on the Twendy_View instance.

Zend_View Helpers
-----------------

In order to provide easy use of Zend_View helpers, Twendy provides two things:

* A `zend` helper variable
* A `zend` tag

The `zend` variable is the instance of Twendy_View, an extension of Zend_View.  Having this variable available in your templates makes it possible to call view helpers in a similar way to Zend_View .phtml scripts.  With native Zend_View scripts you might call the formCheckbox helper with `<?php echo $this->formCheckbox('foo', 'bar'); ?>`, but with Twendy you can use the `zend` variable in the same way `{{ zend.formCheckbox('foo', 'bar')|raw }}`.  You can even pass in array parameters, so `<?php echo $this->formCheckbox('foo', 'bar', array('checked' => true)); ?>` becomes `{{ zend.formCheckbox('foo', 'bar', { checked: true })|raw }}`.

Note the use of the `raw` filter in the examples above.  This is because automatic output escaping is enabled by default in Twig.  The `raw` filter can be omitted if automatic output escaping is disabled or if the code resides within an {% autoescape false %} block.

In many cases, using the `zend` variable in this way will be sufficient, but in certain circumstances it can lead to unexpected results.  Take this example using the Zend_View headScript helper.

`{{ zend.headScript.appendFile('some-file.js') }}`
`{{ zend.headScript.appendFile('some-other-file.js') }}`

The output from the two lines above is:

`<script type="text/javascript" src="some-file.js"></script>`
`<script type="text/javascript" src="some-file.js"></script>`
`<script type="text/javascript" src="some-other-file.js"></script>`

Notice the first file is output twice?  This is because anything between `{{ }}` tags in Twig is printed.  In this instance, the files are being appended to headScript as expected, but the headScript is also being output at the same time which is undesired behaviour.  Any helper class which provides callable configuration methods in this way will suffer from the same problem.

The solution is the included `zend` tag.  Use it in situations where you want to call a helper method without printing to the template immediately.  This is the correct way to write the headScript calls above:

`{% zend headScript.appendFile('some-file.js') %}`
`{% zend headScript.appendFile('some-other-file.js') %}`

When you are ready to output the contents of headScript, use the `zend` variable as before.

`{{ zend.headScript }}`

The syntax of the `zend` tag is `{% zend <helper-name> %}`.  You can chain method calls as you would in native PHP templates `{% zend headScript.appendFile('some-file.js').appendFile('some-other-file.js') %}`.

Custom View Helpers
-------------------

Since Twendy has access to the Twendy_View object (and subsequently Zend_View which it extends), you don't need to do anything special to use your own view helpers in your templates.  Simply register the helper with the view object as you would usually and call it with the `zend` variable or tag as appropriate.

Feedback
--------

Twendy is as much a learning exercise as it is a (hopefully) helpful tool.  Any and all constructive feedback is welcome and will be used to improve Twendy in the future.
