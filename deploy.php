<?php
/**
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @author Adegoke Obasa <goke@cottacush.com>
 */

namespace Deployer;

require 'recipe/yii.php';

serverList('deploy/servers.yml');

set('writable_dirs', ['app/runtime', 'app/web/assets']);
set('shared_dirs', ['app/runtime']);

set('repository', '{{REPO_URL}}');

set('composer_options', 'install --prefer-dist --optimize-autoloader --no-progress --no-interaction');

/**
 * Run migrations
 */
task('deploy:run_migrations', function () {
    run('php {{release_path}}/yii migrate up --interactive=0');
})->desc('Run migrations');

/**
 * Cleanup old releases.
 */
task('deploy:cleanup', function () {
    $releases = get('releases_list');

    $keep = get('keep_releases');

    while ($keep > 0) {
        array_shift($releases);
        --$keep;
    }

    foreach ($releases as $release) {
        run("sudo rm -rf {{deploy_path}}/releases/$release");
    }

    run("cd {{deploy_path}} && if [ -e release ]; then rm release; fi");
    run("cd {{deploy_path}} && if [ -h release ]; then rm release; fi");

})->desc('Cleaning up old releases');

/**
 * Upload env file
 */
task('deploy:upload_environments_file', function () {
    upload(__DIR__ . '/app/env/.env.{{APPLICATION_ENV}}', '{{release_path}}/app/env/.env');
});

/** Yii2 composer setup */
task('deploy:yii2_composer_config', function () {
    // TODO Replace <GITHUB_TOKEN> with valid github token
    run('composer config -g github-oauth.github.com <GITHUB_TOKEN>');
    run('composer global require "fxp/composer-asset-plugin:~1.1.1"');
});

/** Slack Tasks Begin */
task('slack:before_deploy', function () {
    postToSlack('Starting deploy on ' . get('server.name') . '...');
});

task('slack:after_deploy', function () {
    postToSlack('Deploy to ' . get('server.name') . ' done');
});

task('slack:after_migrate', function () {
    postToSlack('Migrations done on ' . get('server.name'));
});

task('slack:before_migrate', function () {
    postToSlack('Running migrations on ' . get('server.name') . '...');
});
/** Slack Tasks End */

/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:yii2_composer_config',
    'deploy:vendors',
    'deploy:upload_environments_file',
    'deploy:run_migrations',
    'deploy:symlink',
    'deploy:writable',
    'deploy:cleanup',
])->desc('Deploy Project');

function postToSlack($message)
{
    $slackHookUrl = get('SLACK_HOOK_URL');
    if (!empty($slackHookUrl)) {
        runLocally('curl -s -S -X POST --data-urlencode payload="{\"channel\": \"#' . get('SLACK_CHANNEL_NAME') .
            '\", \"username\": \"Release Bot\", \"text\": \"' . $message . '\"}"' . get('SLACK_HOOK_URL'));
    } else {
        write('Configure the SLACK_HOOK_URL to post to slack');
    }
}

/**
 * Post to slack if the slack hook URL is not empty
 */
before('deploy:run_migrations', 'slack:before_migrate');
after('deploy:run_migrations', 'slack:after_migrate');
before('deploy', 'slack:before_deploy');
after('deploy', 'slack:after_deploy');
