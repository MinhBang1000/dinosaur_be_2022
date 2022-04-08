<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DinosaurResource;
use App\Models\Dinosaur;
use App\Models\DinosaurCountry;
use App\Models\DinosaurMesozoic;
use App\Models\RolePermission;
use App\Models\UserDinosaur;
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
        // $dinosaurs = Dinosaur::where('decision',1)->get();
        $dinosaurs = Dinosaur::all();
        return $this->sendResponse(DinosaurResource::collection($dinosaurs), 'Index Dinosaur Successful');
    }

    public function homeSearch(Request $request){
        if ($request->has('homeSearch')){
            $dinosaurs = Dinosaur::where('dinosaur_name_en','like','%'.$request->homeSearch.'%')->get();
            return $this->sendResponse(DinosaurResource::collection($dinosaurs),'Home Search Successful');
        }
        return $this->sendError('Not Found',[],404);
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
            'dinosaur_name_en' => $request->dinosaurNameEn,
            'dinosaur_name_vn' => $request->dinosaurNameVn,
            'dinosaur_name_spelling' => $request->dinosaurSpelling,
            'dinosaur_name_explain' => $request->dinosaurExplain,
            'length' => $request->dinosaurLength,
            'weight' => $request->dinosaurWeight,
            'lived' => $request->dinosaurLived,
            'author' => $request->dinosaurAuthor,
            'description_en' => $request->dinosaurDescriptionEn,
            'description_vn' => $request->dinosaurDescriptionVn,
            'food' => $request->dinosaurFood,
            'teeth' => $request->dinosaurTeeth,
            'how_it_move' => $request->dinosaurMove,
            'diet_id' => $request->dinosaurDiet,
            'category_id' => $request->dinosaurCategory,
            'user_id' => $request->userID,
            // decision chưa duyệt bằng 0
            'dinosaur_id' => $request->has('dinosaurID')?$request->dinosaurID:0,
        ]);
        if ($request->has('dinosaurID')){
            // Cập nhật
            $dinosaurCreated = Dinosaur::find($request->dinosaurID);
            if (is_null($request->dinosaurCollection)){
                $dinosaur->collection = $dinosaurCreated->collection;
            }else{
                $dinosaur->collection = count($request->dinosaurCollection);
            }
            $dinosaur->image = $request->hasFile('dinosaurImage')?$request->dinosaurImage->getClientOriginalName():$dinosaurCreated->image;
            $dinosaur->audio = $request->hasFile('dinosaurAudio')?$request->dinosaurAudio->getClientOriginalName():$dinosaurCreated->audio;
            $dinosaur->tmp_record = 1;
            $dinosaur->save();
        }else{
            // Thêm
            $dinosaur->collection = count($request->dinosaurCollection);
            $dinosaur->image = $request->hasFile('dinosaurImage')?$request->dinosaurImage->getClientOriginalName():null;
            $dinosaur->audio = is_null($request->dinosaurAudio)?null:$request->dinosaurAudio->getClientOriginalName();
            $dinosaur->save();
        }
        if ($request->hasFile('dinosaurImage')){
            $request->dinosaurImage->move('images/avatars',$request->dinosaurImage->getClientOriginalName());
        }
        if ($request->hasFile('dinosaurAudio')){
            $request->dinosaurAudio->move('audios/dinosaurs',$request->dinosaurAudio->getClientOriginalName());
        }
        if (!is_null($request->dinosaurCollection)){
            $i = 0;
            foreach ($request->dinosaurCollection as $coid){
                $coid->move('images/collections',$request->dinosaurNameEn.'-'.($i+1).'.jpg');
                $i++;
            }
        }
        foreach ($request->dinosaurMesozoics as $mid) {
            DinosaurMesozoic::create([
                'dinosaur_id' => $dinosaur->id,
                'mesozoic_id' => $mid
            ]);
        }
        foreach ($request->dinosaurCountries as $cid) {
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

    public function decision($id){
        try{
            $dinosaur = Dinosaur::findOrFail($id);
        }catch (NotFoundHttpException $exception){
            return $this->sendError('Model Not Found',$exception,404);
        }
        if (!is_null($dinosaur->updateForDinosaur)){
            // Update
            $dinosaur->tmp_record = 0;
            $dinosaur->save();
            $this->updateForDecision($dinosaur,$dinosaur->updateForDinosaur->id);
            // $this->destroy($dinosaur->id);
        }else{
            // Add
            $dinosaur->decision = 1;
            $dinosaur->save();
        }   
        return $this->sendResponse([],'Decision Dinosaur Successful');
    }

    public function updateForDecision($request, $id)
    {
        try {
            $dinosaur = Dinosaur::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->sendError('Model Not Found', $exception, 400);
        }
        // Update in dinosaur class
        $dinosaur->dinosaur_name_en = empty($request->dinosaur_name_en) ? $dinosaur->dinosaur_name_en : $request->dinosaur_name_en;
        $dinosaur->dinosaur_name_vn = empty($request->dinosaur_name_vn) ? $dinosaur->dinosaur_name_vn : $request->dinosaur_name_vn;
        $dinosaur->dinosaur_name_spelling = empty($request->dinosaur_name_spelling) ? $dinosaur->dinosaur_name_spelling : $request->dinosaur_name_spelling;
        $dinosaur->dinosaur_name_explain = empty($request->dinosaur_name_explain) ? $dinosaur->dinosaur_name_explain : $request->dinosaur_name_explain;
        $dinosaur->length = $request->length==0 ? $dinosaur->length : $request->length;
        $dinosaur->weight = $request->weight==0 ? $dinosaur->weight : $request->weight;
        $dinosaur->author = empty($request->author) ? $dinosaur->author : $request->author;
        $dinosaur->description_en = empty($request->description_en) ? $dinosaur->description_en : $request->description_en;
        $dinosaur->description_vn = empty($request->description_vn) ? $dinosaur->description_vn : $request->description_vn;
        $dinosaur->lived = empty($request->lived) ? $dinosaur->lived : $request->lived;
        $dinosaur->teeth = empty($request->teeth) ? $dinosaur->teeth : $request->teeth;
        $dinosaur->food = empty($request->food) ? $dinosaur->food : $request->food;
        $dinosaur->how_it_move = empty($request->how_it_move) ? $dinosaur->how_it_move : $request->how_it_move;
        $dinosaur->diet_id = empty($request->diet_id) ? $dinosaur->diet_id : $request->diet_id;
        $dinosaur->category_id = empty($request->category_id) ? $dinosaur->category_id : $request->category_id;
        $dinosaur->image = empty($request->image)?$dinosaur->image:$request->image;
        $dinosaur->audio = empty($request->audio)?$dinosaur->audio:$request->audio;
        $dinosaur->collection = $request->collection==0?$dinosaur->collection:$request->collection;
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
        if (!empty($request->countries)) {
            foreach ($dinosaur_countries as $dc){
                $dc->delete();
            }
            foreach ($request->countries as $cid) {
                DinosaurCountry::create([
                    'dinosaur_id' => $dinosaur->id,
                    'country_id' => $cid->id
                ]);
            }
        }
        if (!empty($request->mesozoics)) {
            foreach ($dinosaur_mesozoics as $dm){
                $dm->delete();
            }
            foreach ($request->mesozoics as $mid) {
                DinosaurMesozoic::create([
                    'dinosaur_id' => $dinosaur->id,
                    'mesozoic_id' => $mid->id
                ]);
            }
        }
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
        $dinosaur->dinosaur_name_en = is_null($request->dinosaurNameEn) ? $dinosaur->dinosaur_name_en : $request->dinosaurNameEn;
        $dinosaur->dinosaur_name_vn = is_null($request->dinosaurNameVn) ? $dinosaur->dinosaur_name_vn : $request->dinosaurNameVn;
        $dinosaur->dinosaur_name_spelling = is_null($request->dinosaurSpelling) ? $dinosaur->dinosaur_name_spelling : $request->dinosaurSpelling;
        $dinosaur->dinosaur_name_explain = is_null($request->dinosaurExplain) ? $dinosaur->dinosaur_name_explain : $request->dinosaurExplain;
        $dinosaur->length = is_null($request->dinosaurLength) ? $dinosaur->length : $request->dinosaurLength;
        $dinosaur->weight = is_null($request->dinosaurWeight) ? $dinosaur->weight : $request->dinosaurWeight;
        $dinosaur->author = is_null($request->dinosaurAuthor) ? $dinosaur->author : $request->dinosaurAuthor;
        $dinosaur->description_en = is_null($request->dinosaurDescriptionEn) ? $dinosaur->description_en : $request->dinosaurDescriptionEn;
        $dinosaur->description_vn = is_null($request->dinosaurDescriptionVn) ? $dinosaur->description_vn : $request->dinosaurDescriptionVn;
        $dinosaur->lived = is_null($request->dinosaurLived) ? $dinosaur->lived : $request->dinosaurLived;
        $dinosaur->teeth = is_null($request->dinosaurTeeth) ? $dinosaur->teeth : $request->dinosaurTeeth;
        $dinosaur->food = is_null($request->dinosaurFood) ? $dinosaur->food : $request->dinosaurFood;
        $dinosaur->how_it_move = is_null($request->dinosaurMove) ? $dinosaur->how_it_move : $request->dinosaurMove;
        $dinosaur->diet_id = is_null($request->dinosaurDiet) ? $dinosaur->diet_id : $request->dinosaurDiet;
        $dinosaur->category_id = is_null($request->dinosaurCategory) ? $dinosaur->category_id : $request->dinosaurCategory;
        $dinosaur->save();

        // Update image and collection
        if ($request->hasFile('dinosaurImage')){
            $request->dinosaurImage->move('images/avatars',$request->dinosaurImage->getClientOriginalName());
            $dinosaur->image = $request->dinosaurImage->getClientOriginalName();
            $dinosaur->save();
        }   
        if ($request->hasFile('dinosaurAudio')){
            $request->dinosaurAudio->move('audios/dinosaurs',$request->dinosaurAudio->getClientOriginalName());
            $dinosaur->audio = $request->dinosaurAudio->getClientOriginalName();
            $dinosaur->save();
        }
        if (!is_null($request->dinosaurCollection)){
            $i = 0;
            foreach ($request->dinosaurCollection as $coid){
                $coid->move('images/collections',$request->dinosaurNameEn.'-'.($i+1).'.jpg');
                $i++;
            }
            if ($i!=0){
                $dinosaur->collection = $i;
                $dinosaur->save();
            }
        }

        // Update out dinosaur class
        $dinosaur_mesozoics = DinosaurMesozoic::where('dinosaur_id', $dinosaur->id)->get();
        if (empty($dinosaur_mesozoics)) {
            return $this->sendError('Model Not Found', ['error' => 'This is not match with any DinosaurMesozoic instances'], 400);
        }
        $dinosaur_countries = DinosaurCountry::where('dinosaur_id', $dinosaur->id)->get();
        if (empty($dinosaur_countries)) {
            return $this->sendError('Model Not Found', ['error' => 'This is not match with any DinosaurCountry instances'], 400);
        }
        if (!empty($request->dinosaurCountries)) {
            foreach ($dinosaur_countries as $dc){
                $dc->delete();
            }
            foreach ($request->dinosaurCountries as $cid) {
                DinosaurCountry::create([
                    'dinosaur_id' => $dinosaur->id,
                    'country_id' => $cid
                ]);
            }
        }
        if (!empty($request->dinosaurMesozoics)) {
            foreach ($dinosaur_mesozoics as $dm){
                $dm->delete();
            }
            foreach ($request->dinosaurMesozoics as $mid) {
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

    public function dinosaurLike($dinosaurID,$userID){
        if (!empty($dinosaurID)){
            UserDinosaur::create([
                'dinosaur_id' => $dinosaurID,
                'user_id' => $userID,
            ]);
            return $this->sendResponse([],'Like Dinosaur Successful');
        }
        return $this->sendError('Not Found',[],404);
    }

    public function dinosaurUnlike($dinosaurID,$userID){
        if (!empty($dinosaurID)){
            $postLiked = UserDinosaur::where('dinosaur_id',$dinosaurID)->where('user_id',$userID)->first();
            $postLiked->delete();
            return $this->sendResponse([],'Unlike Dinosaur Successful');
        }
        return $this->sendError('Not Found',[],404);
    }

    public function insert(){
        $n = 34;
        for ($i=1;$i<=$n;$i++){
            RolePermission::create([
                'role_id' => 3,
                'permission_id' => $i,
            ]);
        }
    }
}
