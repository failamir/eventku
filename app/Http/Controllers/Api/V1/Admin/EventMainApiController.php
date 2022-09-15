<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreEventMainRequest;
use App\Http\Requests\UpdateEventMainRequest;
use App\Http\Resources\Admin\EventMainResource;
use App\Models\EventMain;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventMainApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        // abort_if(Gate::denies('event_main_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EventMainResource(EventMain::all());
    }

    public function store(StoreEventMainRequest $request)
    {
        $event = EventMain::create($request->all());

        // if ($request->input('image', false)) {
        //     $event->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        // }

        return (new EventMainResource($event))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EventMain $event)
    {
        // abort_if(Gate::denies('event_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EventMainResource($event);
    }

    public function update(UpdateEventMainRequest $request, EventMain $event)
    {
        $event->update($request->all());

        // if ($request->input('image', false)) {
        //     if (!$event->image || $request->input('image') !== $event->image->file_name) {
        //         if ($event->image) {
        //             $event->image->delete();
        //         }
        //         $event->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        //     }
        // } elseif ($event->image) {
        //     $event->image->delete();
        // }

        return (new EventMainResource($event))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EventMain $event)
    {
        abort_if(Gate::denies('event_main_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
