<?php

namespace Orchid\Platform\Http\Controllers\Install;

use Dotenv\Dotenv;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Orchid\Platform\Http\Controllers\Install\Helpers\EnvironmentManager;
use Orchid\Platform\Http\Controllers\Controller;

class EnvironmentController extends Controller
{
    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environment()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        $dotenv = new Dotenv(base_path());
        $dotenv = $dotenv->load();
        $envArray = [];

        foreach ($dotenv as $value) {
            $chunk = explode('=', $value);
            $envArray[$chunk[0]] = $chunk[1];
        }

        return view('dashboard::container.install.environment', compact('envConfig', 'envArray'));
    }

    /**
     * Processes the newly saved environment configuration and redirects back.
     *
     * @param Request    $input
     * @param Redirector $redirect
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $input, Redirector $redirect)
    {
        $message = $this->EnvironmentManager->saveFile($input);

        return $redirect->route('install::requirements')->with(['message' => $message]);
    }
}