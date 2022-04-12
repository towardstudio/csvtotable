# Section Field plugin for Craft CMS 4.x

Creates a section selector field in the CMS

## Requirements

This plugin requires Craft CMS 4 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

```
cd /path/to/project/craft
```
2. Then tell Composer to load the plugin:

```
composer require bluegg/sectionfield
```

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Website Documentation.


## Usage
```
{% set entries = craft.sectionField.entries(entry.myFieldHandle) %}
{% set sections = craft.sectionField.sections(entry.myFieldHandle) %}

<h2>Entries</h2>
{% for entry in entries.aqll() %}
	<pre>
		{{ dump(entry.title) }}
	</pre>
{% endfor %}

<h2>Sections</h2>
{% for section in sections %}
	<pre>
		{{ dump(section.name) }}
	</pre>
{% endfor %}
```


[Bluegg Disclaimer](https://github.com/Bluegg/bluegg-open-source-disclaimer)

Brought to you by [Bluegg](https://bluegg.co.uk)
