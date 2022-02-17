# notion-content

## Description
Wordpress Plugin for displaying content from Notion using the Notion API.  This plugin assumes you have some knowledge of [Notion](https://notion.so/) and know how to install Wordpress plugins.

This plugin take a simple Notion Database and allows you to display the contents of that database in a Wordpress site.  To view a sample Notion database, [click here](https://www.notion.so/97f601fff6bd453780e3e3f81b2e2829?v=42636979d3d345b0b8ca073587384a9d).  

This plugin was made using the [Wordpress Plugin Boilerplate Generator](https://wppb.me/).

___


## Requirements

### Wordpress Website
[Wordpress](https://wordpress.org/)


### Notion Integration Token
You will need to setup a Notion 
[Notion API Integration](https://www.notion.so/my-integrations)



---

## Installation

1. Install plugin and activate plugin in Wordpress
2. Go to Notion Content -> Setup in the Wordpress admin.
3. Enter in the Notion API Key (aka Internal Integration Token)
4. Enter in the link to the Notion Database.


## Usage
1. Go to Notion Content -> Page Content and click on the "Refresh List" link.
2. The data in your Notion database should now appear 
3. Copy and Paste the shortcode to be used in your Wordpress Post or Page.



## Notion Content Updates
Any changes to your Notion content will not refresh on your Wordpress site right away.  It will be updated on the Wordpress site after 5 minutes automatically.  It can also be manually updated in the Wordpress Admin by clicking on the "Refresh Content" link.  In future updates, this time period will be configurable.  Limiting the update time reduces the amount of API calls to Notion and also improves performance in Wordpress.



---
## Supported Notion Blocks
- Heading 1
- Heading 2
- Heading 3
- Bullet Lists
- Numbered List
- To Do
- Divider
- Quote
- Callout

