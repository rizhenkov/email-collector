<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDomain;
use App\Models\Domain as DomainModel;
use App\Models\UploadList;
use App\Rules\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploadListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UploadList::orderByDesc('upload_time')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'domains' => ['bail', 'required', 'array'],
        ]);

        $validator->sometimes('domains.*', new Domain, function ($input, $item) {
            return substr($item, 0, 4) !== 'http';
        });

        $validator->sometimes('domains.*', 'url', function ($input, $item) {
            return substr($item, 0, 4) === 'http';
        });

        $data = $validator->validate();


        $name = $data['name'];
        $domains = $data['domains'];
        $domainsNames = array_map(function ($domainEntry) {
            if(substr($domainEntry, 0, 4) === 'http') {
                return parse_url($domainEntry, PHP_URL_HOST);
            }
            return $domainEntry;
        }, $domains);

        $uploadList = UploadList::create([
            'name' => $name,
        ]);

        foreach($domainsNames as $domainName){
            $domainModel = DomainModel::firstOrCreate(['domain_name' => $domainName]);
            $uploadList->domains()->attach($domainModel);

            if($domainModel->wasRecentlyCreated){
                // Add to Queue
                ProcessDomain::dispatch($domainModel);
            }
        }

        return [
            'result' => $uploadList->only(['id', 'name']),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $uploadList = UploadList::with('domains', 'domains.contacts')->findOrFail($id);

        return $uploadList;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UploadList  $uploadList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UploadList $uploadList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $list = UploadList::findOrFail($id);
        $list->delete();

        return [
            'success' => true
        ];
    }
}
