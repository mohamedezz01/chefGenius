<?php
// app/Http/Controllers/Api/RecipeSuggestionController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecipeSuggestionController extends Controller
{
    public function suggest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ingredients' => 'required|array|min:1',
            'ingredients.*' => 'string|max:100',
        ]);

        $ingredients = $validated['ingredients'];
        $ingredientString = implode(', ', $ingredients);

        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.0-flash');

        if (!$apiKey) {
            Log::error('Gemini API Key not configured in config/services.php or .env');
            return response()->json(['error' => 'API key not configured.'], 500);
        }
        $prompt = "You are a helpful assistant chef creating recipes based *only* on the ingredients provided. If a common complementary ingredient is absolutely necessary (like salt, pepper, oil, water) and not listed, you may assume it's available in small quantities, but prioritize using only the listed items.\n\nGenerate 2-3 diverse recipe suggestions using the following ingredients:\n{$ingredientString}\n\nFor each recipe suggestion, provide the following details:\n- name: A creative and descriptive name for the recipe (string).\n- description: A short, appealing description (1-2 sentences, string).\n- estimatedPrepTime: A rough estimate of preparation and cooking time (e.g., \"25 mins\", \"1 hour\", string).\n- difficulty: A simple difficulty rating (e.g., \"Easy\", \"Medium\", \"Hard\", string).\n- steps: A list of clear, easy-to-follow instructions (array of strings).\n\nFormat the entire response strictly as a single JSON array of objects. Each object in the array represents one recipe and must contain the keys \"name\", \"description\", \"estimatedPrepTime\", \"difficulty\", and \"steps\". Do not include any text before or after the JSON array.";

        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        try {
            Log::info("Sending request to Gemini API for ingredients: " . $ingredientString);
            $response = Http::timeout(30)->post($apiUrl, [
                'contents' => [['parts' => [['text' => $prompt]]]],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['candidates'][0]['finishReason']) && $responseData['candidates'][0]['finishReason'] !== 'STOP') {
                     Log::error("Gemini API Response - Content blocked or unexpected finish reason: " . $responseData['candidates'][0]['finishReason']);
                     $blockReason = $responseData['candidates'][0]['finishReason'];
                     $safetyFeedback = $responseData['promptFeedback']['safetyRatings'] ?? null;
                     Log::error("Safety Feedback: " . json_encode($safetyFeedback));
                     return response()->json(['error' => 'Content generation stopped due to safety reasons (' . $blockReason . ').'], 400);
                }

                $generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($generatedText) {
                    Log::info("Received successful response from Gemini API.");
                    $recipesJson = preg_replace('/^```json\s*|\s*```$/', '', trim($generatedText));
                    $recipes = json_decode($recipesJson, true); // Decode JSON string

                    // --- SAFER LOGGING ---
                    Log::info('Decoded $recipes variable type: ' . gettype($recipes)); // Log the type
                    Log::info('Decoded $recipes variable content: ' . json_encode($recipes)); // Log the content

                    if (json_last_error() === JSON_ERROR_NONE && is_array($recipes)) {
                        // Log structure only if it's an array and has items
                        if(isset($recipes[0])) {
                            if (is_array($recipes[0])) { // Check if first item is array
                                Log::info('Decoded Recipe Structure (First Item Keys): ' . json_encode(array_keys($recipes[0])));
                                // Check name structure safely
                                if(isset($recipes[0]['name'])) {
                                     if (is_array($recipes[0]['name'])) { // Check if name is array
                                         Log::info('Name structure keys: ' . json_encode(array_keys($recipes[0]['name'])));
                                     } else {
                                         Log::info('Name structure is not an array, value: ' . json_encode($recipes[0]['name']));
                                     }
                                } else {
                                     Log::info('Name key not set in first recipe item.');
                                }
                            } else {
                                 Log::warning('First item in decoded recipes is not an array/object.');
                            }
                        } else {
                             Log::info('Decoded recipes array is empty.');
                        }
                        // --- END SAFER LOGGING ---

                        return response()->json($recipes); // Return the decoded data
                    } else {
                        Log::error("Gemini API Response - Failed to decode JSON. JSON Error: " . json_last_error_msg() . ". Raw Text (after trim): " . $recipesJson);
                        return response()->json(['error' => 'API returned unexpected format.'], 500);
                    }
                } else {
                     if (isset($responseData['promptFeedback']['blockReason'])) {
                         Log::error("Gemini API Response - Prompt blocked. Reason: " . $responseData['promptFeedback']['blockReason']);
                         return response()->json(['error' => 'Request blocked due to safety settings.'], 400);
                     }
                    Log::error("Gemini API Response - Could not extract generated text. Response: " . $response->body());
                    return response()->json(['error' => 'API response format error.'], 500);
                }
            } else {
                Log::error("Gemini API Error - Status: " . $response->status() . " Body: " . $response->body());
                return response()->json(['error' => 'Failed to get suggestions from AI service.'], $response->status());
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Gemini API Connection Error: ' . $e->getMessage());
            return response()->json(['error' => 'Could not connect to AI service.'], 504);
        } catch (\Exception $e) {
            Log::error('Gemini API General Error: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
