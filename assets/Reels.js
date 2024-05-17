$(document).ready(function() {
    const apiKey = 'YOUR_YOUTUBE_API_KEY';
    const query = 'food, recipes'; //more to add
    const maxResults = 10; // Number of videos to fetch initially
    const baseUrl = 'https://www.googleapis.com/youtube/v3/search';

    function fetchYouTubeVideos() {
        $.ajax({
            url: baseUrl,
            method: 'GET',
            data: {
                key: apiKey,
                q: query,
                part: 'snippet',
                type: 'video',
                maxResults: maxResults,
                videoCategoryId: '26' // Category ID for "Howto & Style"
            },
            success: function(data) {
                displayVideos(data.items);
            },
            error: function(response) {
                console.error("Error fetching YouTube videos", response);
            }
        });
    }

    function displayVideos(videos) {
        const reelsContainer = $('#reels-container');
        reelsContainer.empty();
        videos.forEach(video => {
            const videoElement = `
                <div class="reel">
                    <iframe src="https://www.youtube.com/embed/${video.id.videoId}" frameborder="0" allowfullscreen></iframe>
                    <h3>${video.snippet.title}</h3>
                </div>
            `;
            reelsContainer.append(videoElement);
        });
    }

    fetchYouTubeVideos();
});
