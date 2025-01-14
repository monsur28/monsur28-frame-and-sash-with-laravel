<?php

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    // List all contacts
    public function index()
    {
        $contacts = Contact::all();
        return response()->json($contacts, 200);
    }

    // Store a new contact
    public function store(Request $request)
    {
        $validated = $request->validate([
            'icon' => 'required|string|max:255',       // Icon class (e.g., "map-marker-alt")
            'title' => 'required|string|max:255',      // Contact title (e.g., Address, Email)
            'content' => 'required|string|max:1000',   // Description or content
        ]);

        $contact = Contact::create($validated);

        return response()->json(['message' => 'Contact added successfully', 'contact' => $contact], 201);
    }

    // Show a single contact
    public function show($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        return response()->json($contact, 200);
    }

    // Update a contact
    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $validated = $request->validate([
            'icon' => 'sometimes|string|max:255',
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|max:1000',
        ]);

        $contact->update($validated);

        return response()->json(['message' => 'Contact updated successfully', 'contact' => $contact], 200);
    }

    // Delete a contact
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully'], 200);
    }
}
