# CSV to Table plugin for Craft CMS 5.x

Create a table from a CSV

## Requirements

This plugin requires Craft CMS 5 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

```
cd /path/to/project/craft
```

2. Then tell Composer to load the plugin:

```
composer require towardstudio/csvtotable
```

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for CSV to Table.

## Settings

In a Dev environment there is a control panel navigation item which will direct you to the plugin settings.

Within the settings you can set classes on each of the table elements to give a global style.

## Template

```
{{ csvToTable(entry.csvTable) }}
```

To display the table, you can pass the Asset into a function. This will automatically change the data into a table format.

## Additional Parameter

If you don't want your table to display with a `<thead>` you can pass an additional parameter of `false` into the function which will remove the `<thead>` option.

```
{{ csvToTable(entry.csvTable, false) }}
```

[Toward Disclaimer](https://github.com/towardstudio/toward-open-source-disclaimer)

Brought to you by [Toward](https://toward.studio)
