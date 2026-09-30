# KulaAI Vision: head counting

Point the camera at the shed and every animal is counted once, by head, not by ear tag.

Pipeline (same idea as traffic counters such as roboflow/supervision + ByteTrack):

1. **Detect** - COCO-SSD (TensorFlow.js) runs in the browser on each camera frame and boxes every animal.
2. **Track** - `common/js/kula_head_counter.js` (`KulaHeadTracker`) gives each box a persistent ID:
   constant-velocity prediction, two-stage Hungarian matching on IoU (confident detections first,
   then low-confidence ones to recover half-hidden animals), duplicate-box suppression, and
   `minHits` frames before a track is confirmed (flicker is never counted).
3. **Re-identify** - an animal that leaves the view and returns (within 30 s) is matched back to its
   old ID by a coarse colour fingerprint, so it is not counted twice.
4. **Save** - every 2 s the browser posts the list of counted animals to `kula_ai/sync_vision_tracks`;
   each becomes one `ai_vision_session_records` row (`identification_method = 'visual_tracking'`),
   and the session totals feed the usual reconciliation against KulaCRM stock.

Files: `common/vendor/tfjs/` (TensorFlow.js 4.22 and COCO-SSD 2.2.3, Apache-2.0, vendored so no CDN is
needed). The model weights (about 18 MB) are fetched once from Google's `storage.googleapis.com`
(the COCO-SSD default) and cached by the browser; pass `modelUrl` to `KulaHeadCounter` to self-host.

If the on-device model cannot load, the page falls back to the older cloud (Gemini) frame analysis.

Accuracy option: open `/kula_ai/vision?model=accurate` to use the larger COCO-SSD `mobilenet_v2` model (about 65 MB, slower). In a test on a sample photo it found 3 of 3 horses where the default lite model found 2.

Limits: COCO-SSD knows cow, sheep, horse, dog, cat, bird (and a few wild animals). Goats and pigs are
usually detected as sheep/cow/dog, so check the count on the first sessions. Animals fully hidden behind
others cannot be seen. Tests: `node tests/head_counter.test.js`.
