<?php
/**
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @author Adegoke Obasa <goke@cottacush.com>
 */

require 'recipe/yii.php';

serverList('deploy/servers.yml');

set('writable_dirs', ['runtime', 'web/assets']);

env('composer_options', 'install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction');

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
    $releases = env('releases_list');

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
    upload(env('local_path') . '/env/.env.{{APPLICATION_ENV}}', '{{release_path}}/env/.env');
});

/** Slack Tasks Begin */
task('slack:before_deploy', function () {
    postToSlack('Starting deploy on ' . env('server.name') . '...');
});

task('slack:after_deploy', function () {
    postToSlack('Deploy to ' . env('server.name') . ' done');
});

task('slack:after_migrate', function () {
    postToSlack('Migrations done on ' . env('server.name'));
});

task('slack:before_migrate', function () {
    postToSlack('Running migrations on ' . env('server.name') . '...');
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
    'deploy:vendors',
    'deploy:upload_environments_file',
    'deploy:run_migrations',
    'deploy:symlink',
    'deploy:writable',
    'deploy:cleanup',
])->desc('Deploy Project');

$repositoryUrl = env('REPO_URL');
set('repository', $repositoryUrl);

function postToSlack($message)
{
    runLocally('curl -s -S -X POST --data-urlencode payload="{\"channel\": \"#' . env('SLACK_CHANNEL_NAME') . '\", \"username\": \"Release Bot\", \"text\": \"' . $message . '\"}"' . env('SLACK_HOOK_URL'));
}

/**
 * Post to slack if the slack hook URL is not empty
 */
if (!empty($slackHookUrl)) {
    before('deploy:run_migrations', 'slack:before_migrate');
    after('deploy:run_migrations', 'slack:after_migrate');
    before('deploy', 'slack:before_deploy');
    after('deploy', 'slack:after_deploy');
}
