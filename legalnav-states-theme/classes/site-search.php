<?php
    class SiteSearch {
        private $query = null;

        function __construct($query) {
            $this->setQuery($query);
        }

        private function setQuery($query) {
            $this->query = $query;
        }

        private function buildTitleMatchQueryArgs($id) {
            return [
                'post_type' => ['ln_resource', 'guided_assistant'],
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
                's' => $this->query,
                'tax_query' => [
                    [
                        'taxonomy' => 'states',
                        'field' => 'id',
                        'terms' => $id,
                    ]
                ],
                'fields' => 'ids'
            ];
        }

        // Gets topic ids of topics with query in keyword or title
        private function getTopicsWithQueryInKeyword() {
            return array_unique(array_merge(
                // Topic IDs w/ query in topic keywords
                get_terms([
                    'fields' => 'ids',
                    'taxonomy' => 'topics',
                    'meta_query' => [
                        [
                            'key' => 'keyword',
                            'value' => $this->query,
                            'compare' => 'LIKE',
                        ]
                    ],
                    'hide_empty' => false,
                ]),

                // Topic IDs w/ query in topic name
                get_terms([
                    'fields' => 'ids',
                    'taxonomy' => 'topics',
                    'name__like' => $this->query,
                    'hide_empty' => false,
                ])
            ));
        }

        private function buildTopicMatchQueryArgs($id) {
            return [
                'post_type' => ['ln_resource', 'guided_assistant'],
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
                'tax_query' => [
                    'relation' => 'AND',
                    [
                        'taxonomy' => 'states',
                        'field' => 'id',
                        'terms' => $id
                    ],
                    [
                        'taxonomy' => 'topics',
                        'field' => 'id',
                        'terms' => $this->getTopicsWithQueryInKeyword()
                    ],
                ],
                'fields' => 'ids'
            ];
        }

        public function getResults($id = '') {

            $user_state_id = $_SESSION['user_state_id'];
			if (!$user_state_id) { $user_state_id = $id; }

            // Posts where title or content matches query
            $title_match_ids = (new WP_Query($this->buildTitleMatchQueryArgs($user_state_id)))->posts;

            // Posts where topic name or topic keywords match query
            $topic_match_ids = (new WP_Query($this->buildTopicMatchQueryArgs($user_state_id)))->posts;

            // Merge query results and sort
            $result_ids = array_unique(array_merge($title_match_ids, $topic_match_ids));

            $result_objs = [];

            foreach($result_ids as $result_id) {
                // If 'form', 'guided-assistant', 'related-readings', or 'video' get content, else get overview for desc
                $result_resource_type = get_the_terms($result_id, 'resource_type')[0]->slug;
                $desc = (in_array($result_resource_type, ['form', 'guided-assistant', 'related-readings', 'video']))
                            ? get_post($result_id)->post_content
                            : get_field('overview', $result_id);

                $is_ls_resource = get_field('is_ls_resource', $result_id);

                $result = [
                    'title' => get_the_title($result_id),
                    'desc' => ($desc) ? substr(strip_tags($desc), 0, 150) . '...' : '',
                    'is_ls_resource' => $is_ls_resource,
                    'url' => get_permalink($result_id)
                ];
                array_push($result_objs, $result);
            }

            return $result_objs;
        }
    }
?>
