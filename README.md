# 10up UI Kit Scaffold

This scaffold is the starting point for all 10up UI Kit WordPress projects.

It contains a bare bones child theme. Asset bundling is handled entirely by [10up Toolkit](https://github.com/10up/10up-toolkit).

## Requirements

- Node >= 18
- NPM >= 9

## How to Use

*The best way to use the scaffold is to simply run `npx 10up-toolkit project init` in your terminal.*

You can also use the scaffold manually by doing the following:

1. [Download a zip](https://github.com/10up/ui-kit-scaffold/archive/trunk.zip) of the repository into your project. At 10up, by default we version control the `wp-content` directory (ignoring obvious things like `uploads`). This enables us to have plugins, theme, etc. all in one repository. Having separate repositories for each plugin and theme only happens in rare circumstances that are outside of our control.
2. Take what you need. If your project doesn't have a theme, remove the theme. If your project doesn't need any plugin functionality, remove the MU plugin. If your plugin doesn't need CSS/JS, remove it. If your plugin doesn't need to be translated, remove all the translation functionality.
3. Compiling, minifying, bundling, etc. of JavaScript and CSS is all done by [10up Toolkit](https://github.com/10up/10up-toolkit). 10up Toolkit is included as a dev dependency in both the plugin and theme. If you want to develop on the theme (and vice-versa the plugin), you would navigate to the theme directory in your console and run `npm run start` (after running `npm install` first of course). Inside `package.json` edit `10up-toolkit.devURL` to your local development URL for if you're not using a `.test`. `10up-toolkit.entry` are the paths to CSS/JS files that need to be bundled. Edit these as needed.
4. Make sure to add `define( 'SCRIPT_DEBUG', true )` to `wp-config.php` to enable Hot Module Reload and React Fast Refresh.
5. [npm workspaces](https://docs.npmjs.com/cli/v7/using-npm/workspaces) is used to manage npm dependencies. The main benefit of using npm workspaces is that we can hoist all dependencies to the root folder and avoid installing duplicate dependencies, saving time and space. By default the `workspaces` config are setup so that `mu-plugins/10up-plugin` and all themes are treated as "packages", if you are building a new plugin/theme make sure to update `workspaces` in `package.json` See the example below:

```json
  "workspaces": [
  "themes/*",
  "mu-plugins/10up-plugin",
  "mu-plugins/my-other-awesome-10up-plugin",
  ],
```

6. To build plugins/themes simply run `npm install` at the root and `npm run [build|start|watch]` and npm will automatically build all themes and plugins. If a WordPress critical error is received run `composer install` in all locations that have an existing `composer.lock` file; example locations: `root`, `/mu-plugins/10up-plugin`, `/themes/10up-theme`. Upon build completion set the `10up-theme` as active within WordPress admin by running `wp theme activate 10up-theme`.
7. `npm workspaces` do not have the ability to run scripts from multiple packages in parrallel. Because of that we use the `npm-run-all` package and we define specific scripts in `package.json` so you will need to update the `watch:*` scripts in `package.json` and replace `tenup-theme` and `tenup-plugin` with the actual package names.

```json
 "watch:theme": "npm run watch -w=tenup-theme",
 "watch:plugin": "npm run watch -w=tenup-plugin",
 "watch": "run-s watch:theme watch:plugin",
```

7. To add npm dependencies to your theme and/or plugins add the `-w=package-name` flag to the `npm install` command. E.g: `npm install --save prop-types -w=tenup-plugin` **DO NOT RUN** `npm install` inside an individual workspace/package. Always run the from the root folder.
8. If you're building Gutenberg blocks and importing `@wordpress/*` packages, **you do not** need to manually install them as `10up-toolkit` will handle these packages properly.

For more information on setting up the UI Kit plugin and theme with Composer, see the [main repository readme](https://github.com/10up/ui-kit/blob/develop/readme.md).
