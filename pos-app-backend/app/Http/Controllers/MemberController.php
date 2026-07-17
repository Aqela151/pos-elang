<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->filled('cabang_id')) {
            $data['cabang_id'] = $request->cabang_id;
        }

        $member = Member::create($data);

        return response()->json([
            'message' => 'Member berhasil ditambahkan',
            'data' => $member
        ], 201);
    }

    public function show(Member $member)
    {
        return $member;
    }

    public function update(Request $request, Member $member)
    {
        $data = $request->all();

        if (! $request->has('cabang_id')) {
            unset($data['cabang_id']);
        }

        $member->update($data);

        return response()->json([
            'message' => 'Member berhasil diupdate',
            'data' => $member
        ]);
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return response()->json([
            'message' => 'Member berhasil dihapus'
        ]);
    }
}