<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DinosaurResource;
use App\Models\Dinosaur;
use App\Models\DinosaurCountry;
use App\Models\DinosaurMesozoic;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DinosaurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dinosaurs = Dinosaur::all();
        return $this->sendResponse(DinosaurResource::collection($dinosaurs), 'Index Dinosaur Successful');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dinosaur = Dinosaur::create([
            'dinosaur_name_en' => $request->dinosaur_name_en,
            'dinosaur_name_vn' => $request->dinosaur_name_vn,
            'length' => $request->length,
            'weight' => $request->weight,
            'lived' => $request->lived,
            'author' => $request->author,
            'description_en' => $request->description_en,
            'description_vn' => $request->description_vn,
            'food' => $request->food,
            'teeth' => $request->teeth,
            'how_it_move' => $request->how_it_move,
            'diet_id' => $request->diet_id,
            'category_id' => $request->category_id
        ]);

        foreach ($request->mesozoics_id as $mid) {
            DinosaurMesozoic::create([
                'dinosaur_id' => $dinosaur->id,
                'mesozoic_id' => $mid
            ]);
        }
        foreach ($request->countries_id as $cid) {
            DinosaurCountry::create([
                'dinosaur_id' => $dinosaur->id,
                'country_id' => $cid
            ]);
        }
        // this is return a result of dinosaur
        return $this->sendResponse(new DinosaurResource($dinosaur), 'Store Dinosaur Successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $dinosaur = Dinosaur::findOrFail($id);
        }catch (NotFoundHttpException $exception){
            return $this->sendError('Model Not Found',$exception,404);
        }
        return $this->sendResponse(new DinosaurResource($dinosaur),'Show Dinosaur Successful');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $dinosaur = Dinosaur::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->sendError('Model Not Found', $exception, 400);
        }
        // Update in dinosaur class
        $dinosaur->dinosaur_name_en = is_null($request->dinosaur_name_en) ? $dinosaur->dinosaur_name_en : $request->dinosaur_name_en;
        $dinosaur->dinosaur_name_vn = is_null($request->dinosaur_name_vn) ? $dinosaur->dinosaur_name_vn : $request->dinosaur_name_vn;
        $dinosaur->length = is_null($request->length) ? $dinosaur->length : $request->length;
        $dinosaur->weight = is_null($request->weight) ? $dinosaur->weight : $request->weight;
        $dinosaur->author = is_null($request->author) ? $dinosaur->author : $request->author;
        $dinosaur->description_en = is_null($request->description_en) ? $dinosaur->description_en : $request->description_en;
        $dinosaur->description_vn = is_null($request->description_vn) ? $dinosaur->description_vn : $request->description_vn;
        $dinosaur->lived = is_null($request->lived) ? $dinosaur->lived : $request->lived;
        $dinosaur->teeth = is_null($request->teeth) ? $dinosaur->teeth : $request->teeth;
        $dinosaur->food = is_null($request->food) ? $dinosaur->food : $request->food;
        $dinosaur->how_it_move = is_null($request->how_it_move) ? $dinosaur->how_it_move : $request->how_it_move;
        $dinosaur->diet_id = is_null($request->diet_id) ? $dinosaur->diet_id : $request->diet_id;
        $dinosaur->category_id = is_null($request->category_id) ? $dinosaur->category_id : $request->category_id;
        $dinosaur->save();

        // Update out dinosaur class
        $dinosaur_mesozoics = DinosaurMesozoic::where('dinosaur_id', $dinosaur->id)->get();
        if (empty($dinosaur_mesozoics)) {
            return $this->sendError('Model Not Found', ['error' => 'This is not match with any DinosaurMesozoic instances'], 400);
        }
        $dinosaur_countries = DinosaurCountry::where('dinosaur_id', $dinosaur->id)->get();
        if (empty($dinosaur_countries)) {
            return $this->sendError('Model Not Found', ['error' => 'This is not match with any DinosaurCountry instances'], 400);
        }
        if (!empty($request->countries_id)) {
            foreach ($dinosaur_countries as $dc){
                $dc->delete();
            }
            foreach ($request->countries_id as $cid) {
                DinosaurCountry::create([
                    'dinosaur_id' => $dinosaur->id,
                    'country_id' => $cid
                ]);
            }
        }
        if (!empty($request->mesozoics_id)) {
            foreach ($dinosaur_mesozoics as $dm){
                $dm->delete();
            }
            foreach ($request->mesozoics_id as $mid) {
                DinosaurMesozoic::create([
                    'dinosaur_id' => $dinosaur->id,
                    'mesozoic_id' => $mid
                ]);
            }
        }
        return $this->sendResponse(new DinosaurResource($dinosaur), 'Update Dinosaur Successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $dinosaur = Dinosaur::findOrFail($id);
        }catch (ModelNotFoundException $exception){
            return $this->sendError('Model Not Found', $exception, 400);
        }
        $dinosaur_mesozoics = DinosaurMesozoic::where('dinosaur_id',$dinosaur->id)->get();
        if (!empty($dinosaur_mesozoics)){
            foreach ($dinosaur_mesozoics as $dm){
                $dm->delete();
            }
        }
        $dinosaur_countries = DinosaurCountry::where('dinosaur_id',$dinosaur->id)->get();
        if (!empty($dinosaur_countries)){
            foreach ($dinosaur_countries as $dc){
                $dc->delete();
            }
        }
        $dinosaur->delete();
        return $this->sendResponse([],'Delete Dinosaur Successful');
    }
}
