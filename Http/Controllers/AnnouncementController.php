<?php

namespace App\Bots\pozorprace_bot\Http\Controllers;

use App\Bots\pozorprace_bot\Models\PraceAnnouncement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Romanlazko\Telegram\App\Telegram;
use Romanlazko\Telegram\Exceptions\TelegramException;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Telegram $telegram, Request $request)
    {
        $search = strtolower($request->search);
        
        $announcements = PraceAnnouncement::orderByDesc('created_at')
            ->when(!empty($request->input('search')), function($query) use($search) {
                return $query->where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(title) LIKE ?', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(caption) LIKE ?', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(education) LIKE ?', ['%' . $search . '%'])
                        ->orWhereHas('chat', function ($query) use ($search) {
                            $query->whereRaw('LOWER(first_name) LIKE ?', ['%' . $search . '%'])
                                ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . $search . '%'])
                                ->orWhereRaw('LOWER(username) LIKE ?', ['%' . $search . '%'])
                                ->orWhereRaw('LOWER(chat_id) LIKE ?', ['%' . $search . '%']);
                        });
                });
            })
            ->with('chat')
            ->paginate(50);

        $announcements_collection = $announcements->map(function ($announcement) use ($telegram){
            $announcement->chat = $announcement->chat()->first();
            $announcement->chat->photo = $telegram::getPhoto(['file_id' => $announcement->chat->photo]);
            return $announcement;
        });
        

        return view('pozorprace_bot::announcement.index', compact(
            'announcements',
            'announcements_collection'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//     public function create()
//     {
//         return view('telegram::advertisement.create');
//     }

//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function store(AdvertisementRequest $request)
//     {
//         $images = [];

//         $advertisement = Advertisement::create(
//             Arr::except($request->validated(), ['images'])
//         );

//         if ($request->hasFile('images')) {
//             foreach ($request->file('images') as $image) {
//                 $images[] = ['url' => Storage::url($image->store('public/advertisements'))];
//             }
//             $advertisement->images()->createMany(
//                 $images
//             );
//         }

//         return redirect()->route('advertisement.index')->with([
//             'ok' => true,
//             'description' => "Advertisement succesfuly created"
//         ]);
//     }

//     /**
//      * Display the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function show(Advertisement $advertisement, Telegram $telegram, SendAdvertisement $sendAdvertisement)
//     {
//         $admins = User::pluck('chat_id');

//         foreach ($admins as $admin) {
//             try {
//                 if ($admin) {
//                     $response[$admin] = $sendAdvertisement($telegram, $advertisement, $admin);
//                 }
//             }
//             catch (TelegramException $e) {
//                 $response[$admin] = $e->getMessage();
//             }
//         }
//         dd($response);
//     }

//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function edit(Advertisement $advertisement)
//     {
//         // $distributions = $advertisement->distributions()->orderByDesc('created_at')->paginate(20);

//         // $distributions->each(function($distribution){
//         //     $distribution->chat_ids = collect(json_decode($distribution->chat_ids));
//         //     $distribution->results = collect(json_decode($distribution->results));
//         //     $distribution->chats_count = $distribution->chat_ids->count();
//         //     $distribution->ok_count = $distribution->results->where('ok', true)->count();
//         //     $distribution->false_count = $distribution->results->where('ok', false)->count();
//         // });

//         return view('telegram::advertisement.edit', compact(
//             'advertisement',
//             // 'distributions'
//         ));
//     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function update(AdvertisementRequest $request, Advertisement $advertisement)
//     {
//         $images = [];

//         $advertisement->update(
//             Arr::except($request->validated(), ['images'])
//         );

//         if ($request->has('delete_images')){
//             foreach ($request->delete_images as $id) {
//                 $image = $advertisement->images()->find($id);
//                 $filePath = str_replace('/storage', 'public', $image->url);
//                 if (Storage::delete($filePath)){
//                     $image->delete();
//                 }
//             }
//         }

//         if ($request->hasFile('images')) {
//             foreach ($request->file('images') as $image) {
//                 $images[] = ['url' => Storage::url($image->store('public/advertisements'))];
//             }

//             $advertisement->images()->createMany(
//                 $images
//             );
//         }

//         return redirect()->route('advertisement.index')->with([
//             'ok' => true,
//             'description' => "Advertisement succesfuly updated"
//         ]);
//     }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy(Advertisement $advertisement)
//     {
//         $images = $advertisement->images;

//         foreach ($images as $image) {
//             $filePath = str_replace('/storage', 'public', $image->url);
//             if (Storage::delete($filePath)){
//                 $image->delete();
//             }
//         }

//         $advertisement->delete();

//         return redirect()->route('advertisement.index')->with([
//             'ok' => true,
//             'description' => "Advertisement succesfuly deleted"
//         ]);
//     }
}
