<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateGalleryImageRequest;
use App\Http\Requests\UpdateGalleryImageRequest;
use App\Repositories\GalleryImageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class GalleryImageController extends AppBaseController
{
    /** @var  GalleryImageRepository */
    private $galleryImageRepository;

    public function __construct(GalleryImageRepository $galleryImageRepo)
    {
        $this->galleryImageRepository = $galleryImageRepo;
    }

    /**
     * Display a listing of the GalleryImage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $galleryImages = $this->galleryImageRepository->all();

        return view('admin.gallery_images.index')
            ->with('galleryImages', $galleryImages);
    }

    /**
     * Show the form for creating a new GalleryImage.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.gallery_images.create');
    }

    /**
     * Store a newly created GalleryImage in storage.
     *
     * @param CreateGalleryImageRequest $request
     *
     * @return Response
     */
    public function store(CreateGalleryImageRequest $request)
    {
        $input = $request->all();

        $galleryImage = $this->galleryImageRepository->create($input);

        Flash::success('Gallery Image saved successfully.');

        return redirect(route('galleryImages.index'));
    }

    /**
     * Display the specified GalleryImage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $galleryImage = $this->galleryImageRepository->find($id);

        if (empty($galleryImage)) {
            Flash::error('Gallery Image not found');

            return redirect(route('galleryImages.index'));
        }

        return view('admin.gallery_images.show')->with('galleryImage', $galleryImage);
    }

    /**
     * Show the form for editing the specified GalleryImage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $galleryImage = $this->galleryImageRepository->find($id);

        if (empty($galleryImage)) {
            Flash::error('Gallery Image not found');

            return redirect(route('galleryImages.index'));
        }

        return view('admin.gallery_images.edit')->with('galleryImage', $galleryImage);
    }

    /**
     * Update the specified GalleryImage in storage.
     *
     * @param int $id
     * @param UpdateGalleryImageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGalleryImageRequest $request)
    {
        $galleryImage = $this->galleryImageRepository->find($id);

        if (empty($galleryImage)) {
            Flash::error('Gallery Image not found');

            return redirect(route('galleryImages.index'));
        }

        $galleryImage = $this->galleryImageRepository->update($request->all(), $id);

        Flash::success('Gallery Image updated successfully.');

        return redirect(route('galleryImages.index'));
    }

    /**
     * Remove the specified GalleryImage from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $galleryImage = $this->galleryImageRepository->find($id);

        if (empty($galleryImage)) {
            Flash::error('Gallery Image not found');

            return redirect(route('galleryImages.index'));
        }

        $this->galleryImageRepository->delete($id);

        Flash::success('Gallery Image deleted successfully.');

        return redirect(route('galleryImages.index'));
    }
}
