<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        return Member::all();
    }

    public function store(Request $request)
    {
        $member = Member::create($request->all());

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
        $member->update($request->all());

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