<?php

namespace App\Http\Controllers\Concerns;

trait RedirectsControllerActions
{
    /**
     * Redirects to action on this controller
     * @param string $action controller method
     * @param $parameters
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAction($action, $parameters = [], $status = 302, array $headers = [])
    {
        $controllerAction = '\\' . get_called_class() . '@' . $action;
        return redirect()->action($controllerAction, $parameters, $status, $headers);
    }
}
