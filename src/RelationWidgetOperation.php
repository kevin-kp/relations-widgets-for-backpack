<?php

namespace izica\RelationsWidgets;

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

trait RelationWidgetOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $segment  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current CrudController.
     */
    protected function setupRelationWidgetRoutes($segment, $routeName, $controller)
    {
        Route::put($segment.'/{id}/order', [
            'as'        => $routeName.'.order',
            'uses'      => $controller.'@order',
            'operation' => 'RelationWidget',
        ]);
    }
    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupRelationWidgetDefaults()
    {
        // allow access to the operation
        $this->crud->allowAccess('RelationWidget');

        $this->crud->operation('RelationWidget', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });
    }

    public function order($id) {
        $this->crud->hasAccessOrFail('RelationWidget');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        $data = $request->all();
        $orders = $data['orders'];
        $field_name = $data['relation_name'];
        $model = $this->crud->getEntryWithLocale($id);
        if (is_null($model->{$field_name})) {
            abort(400);
        }
        foreach ($model->{$field_name} as $field) {
            if (!array_key_exists($field->id, $orders)) {
                continue;
            }
            $order_value = intval($orders[$field->id]);
            $field->order = $order_value;
            $field->save();
        }
        return response(null, Response::HTTP_NO_CONTENT);
    }
}