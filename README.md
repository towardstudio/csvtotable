# CVS to Table plugin for Craft CMS 3.x

Create a table from a CSV

## Requirements

This plugin requires Craft CMS 3 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

```
cd /path/to/project/craft
```

2. Then tell Composer to load the plugin:

```
composer require bluegg/csvtotable
```

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for CSV to Table.

## Settings

In a Dev environment there is a control panel navigation item which will direct you to the plugin settings.

Within the settings you can set classes on each of the table elements to give a global style.

## Usage

When creating a field, you will see a new CSV Table option. This is similar to an Assets field.

Within an entry/category etc, you can add a CSV, XML, Excel file to be uploaded.

## Template

```
{{ csvToTable(entry.csvTable) }}
```

To display the table, you can pass the Asset into a function. This will automatically change the data into a table format.

## Additional Parameter

If you don't want your table to display with a <thead> you can pass an additional parameter of `false` into the function which will remove the <thead> option.

```
{{ csvToTable(entry.csvTable, false) }}
```

[Bluegg Disclaimer](https://github.com/Bluegg/bluegg-open-source-disclaimer)

Brought to you by [Bluegg](https://bluegg.co.uk)
