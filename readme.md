# [Federated Login v1.0.0]

for Moodle 2.6 or higher

The Federated Login Moodle block facilitates the use of "default home" cookies on Moodle sites that use a federated Shibboleth login.

In a federated Shibboleth environment users are typically directed first to a WAYF page where they choose which institution manages their account. It is possible to offer users the ability to set a default home cookie so that they can bypass this step when they use the browser where the cookie has been set. This block does not provide a WAYF page or the cookie management interface, but it allows you to direct users to an external management screen that you have set up independently.

## Installation

Unzip files into your Moodle blocks directory. This will create a folder called federated_login. Alternatively, you may install this block with git following whatever practice you prefer. For instance, in the top-level folder of your Moodle install, you could type the command: 

<pre><code>git clone https://github.com/kwiliarty/moodle-block_federated_login.git blocks/federated_login</code></pre>

Then visit the admin screen to allow the install to complete.

## Configuration ##

To configure the block, go to Site Administration > Modules > Blocks > Federated Login.

### Home cookie name ###

Choose a name for your default home cookie (e.g. _redirect_user_idp)

### Home cookie manager ###

Provide a link to the page where your users can set or clear a default home cookie.

### Help link text ###

If you have a page with help for users you can link to it. This setting allow you to determine the display text for your help page link.

### Help link URL ###

If you have a page with help for users you can provide the URL for it here.

### Number of schools ###

Enter the number of schools handled in your federation environment and then save the change to load the correct number of school-specific settings on the rest of the page.

### Settings for school X ###

You will need to enter three values for each school in your federated environment.

#### School id X ####

Choose a string that you will not change. Setting an id this way allows the display name to be modified when desired.

#### School name X ####

Enter the display name for the school.

#### School IDP X ####

Enter the URL for the school-specific IDP

## Changing the title of the block ##

Individual block instances can have a customized title. To change the display title of a block, turn editing on on a screen that displays the block and click on the (gear) icon to edit the settings.

## Changelog ##

### [v1.0.0] ###
* Initial release
* Compatible with Moodle v2.6*
