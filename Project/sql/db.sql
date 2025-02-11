DROP SCHEMA IF EXISTS raffia CASCADE;

CREATE SCHEMA raffia;

SET search_path TO raffia;

CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    description TEXT,
    email TEXT UNIQUE NOT NULL,
    image TEXT,
    is_blocked BOOLEAN NOT NULL DEFAULT FALSE,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
    remember_token TEXT DEFAULT NULL
);

CREATE TABLE restaurant_type (
    id SERIAL PRIMARY KEY,
    name TEXT UNIQUE NOT NULL
);

CREATE TABLE restaurant (
    id INTEGER PRIMARY KEY REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    rating_average FLOAT,
    type_id INTEGER NOT NULL REFERENCES restaurant_type(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    capacity INTEGER NOT NULL CHECK (capacity > 0)
);

CREATE TABLE client (
    id INTEGER PRIMARY KEY REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "group" (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    is_public BOOLEAN NOT NULL,
    owner_id INTEGER REFERENCES client(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    datetime TIMESTAMP NOT NULL DEFAULT NOW(),
    content TEXT NOT NULL,
    images JSONB
);

CREATE TABLE review_post (
    id INTEGER PRIMARY KEY REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    rating INTEGER NOT NULL CHECK (rating >= 0 AND rating <= 5),
    client_id  INTEGER REFERENCES client(id) ON UPDATE CASCADE ON DELETE CASCADE,
    restaurant_id  INTEGER REFERENCES restaurant(id) ON UPDATE CASCADE ON DELETE CASCADE,
    group_id INTEGER REFERENCES "group"(id) ON UPDATE CASCADE ON DELETE CASCADE DEFAULT NULL
);

CREATE TABLE informational_post  (
    id INTEGER PRIMARY KEY REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    restaurant_id INTEGER REFERENCES restaurant(id) ON UPDATE CASCADE ON DELETE CASCADE,
    pinned BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    datetime TIMESTAMP NOT NULL DEFAULT NOW(),
    post_id INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    user_id INTEGER REFERENCES "user"(id) ON UPDATE CASCADE
);

CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    datetime TIMESTAMP NOT NULL DEFAULT NOW(),
    content TEXT NOT NULL,
    viewed BOOLEAN NOT NULL DEFAULT FALSE,
    user_id INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE request_notification  (
    id INTEGER PRIMARY KEY REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE like_notification  (
    id INTEGER PRIMARY KEY REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE comment_notification (
    id INTEGER PRIMARY KEY REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    comment_id  INTEGER REFERENCES comment(id)  ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE general_notification (
    id INTEGER PRIMARY KEY REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE join_requests (
    client_id INTEGER NOT NULL REFERENCES client(id) ON DELETE CASCADE,
    group_id INTEGER NOT NULL REFERENCES "group"(id) ON DELETE CASCADE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (client_id, group_id)
);

CREATE TABLE group_invitations (
    id SERIAL PRIMARY KEY,
    client_id INTEGER NOT NULL REFERENCES client(id) ON DELETE CASCADE,
    group_id INTEGER NOT NULL REFERENCES "group"(id) ON DELETE CASCADE,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE join_group_notification  (
    id INTEGER PRIMARY KEY REFERENCES request_notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    client_id  INTEGER,
    group_id INTEGER,
    FOREIGN KEY (client_id, group_id ) REFERENCES join_requests(client_id, group_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE request_follow  (
    datetime TIMESTAMP NOT NULL DEFAULT NOW(),
    requester_client_id  INTEGER REFERENCES client(id) ON UPDATE CASCADE ON DELETE CASCADE,
    receiver_client_id  INTEGER REFERENCES client(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (requester_client_id, receiver_client_id )
);

CREATE TABLE follow_notification (
    id INTEGER PRIMARY KEY REFERENCES request_notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    sender_client_id  INTEGER,
    receiver_client_id  INTEGER,
    FOREIGN KEY (sender_client_id, receiver_client_id) REFERENCES request_follow(requester_client_id, receiver_client_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE like_post  (
    datetime TIMESTAMP NOT NULL DEFAULT NOW(),
    user_id INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    post_id  INTEGER REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (user_id, post_id)
);

CREATE TABLE like_post_notification  (
    id INTEGER PRIMARY KEY REFERENCES like_notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    user_id  INTEGER,
    post_id  INTEGER,
    FOREIGN KEY (user_id, post_id) REFERENCES like_post(user_id , post_id ) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE like_comment  (
    datetime TIMESTAMP NOT NULL DEFAULT NOW(),
    user_id  INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    comment_id  INTEGER REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (user_id , comment_id )
);

CREATE TABLE like_comment_notification  (
    id INTEGER PRIMARY KEY REFERENCES like_notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    user_id  INTEGER,
    comment_id  INTEGER,
    FOREIGN KEY (user_id, comment_id ) REFERENCES like_comment(user_id, comment_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE appeal_unblock_notification (
    id INTEGER PRIMARY KEY REFERENCES notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    user_blocked_id INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE admin_notification  (
    id INTEGER PRIMARY KEY REFERENCES general_notification(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE group_notification  (
    id INTEGER PRIMARY KEY REFERENCES general_notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    group_id  INTEGER REFERENCES "group"(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE follows_restaurant  (
    client_id  INTEGER REFERENCES client(id) ON UPDATE CASCADE ON DELETE CASCADE,
    restaurant_id  INTEGER REFERENCES restaurant(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (client_id, restaurant_id )
);

CREATE TABLE follows_client  (
    sender_client_id  INTEGER REFERENCES client(id) ON UPDATE CASCADE ON DELETE CASCADE,
    followed_client_id  INTEGER REFERENCES client(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (sender_client_id , followed_client_id)
);

CREATE TABLE started_following_client_notification(
    id INTEGER PRIMARY KEY REFERENCES general_notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    sender_id INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE comment_relationship  (
    child INTEGER REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    parent INTEGER REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (child, parent)
);

CREATE TABLE group_member  (
    client_id  INTEGER REFERENCES client(id) ON UPDATE CASCADE ON DELETE CASCADE,
    group_id  INTEGER REFERENCES "group"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (client_id, group_id )
);

CREATE TABLE started_following_restaurant_notification(
    id INTEGER PRIMARY KEY REFERENCES general_notification(id) ON UPDATE CASCADE ON DELETE CASCADE,
    sender_id INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE
);



CREATE INDEX notified_user_notification ON notification USING btree (user_id);
CLUSTER notification USING notified_user_notification;

CREATE INDEX idx_client_review ON review_post USING hash(client_id);
CREATE INDEX idx_receiver_notification ON notification USING hash(user_id);
CREATE INDEX idx_type_restaurant ON restaurant(type_id); 
CLUSTER restaurant USING idx_type_restaurant;

ALTER TABLE "user" ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.username), 'B') ||
            setweight(to_tsvector('english', NEW.description), 'C')
        );
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.username <> OLD.username OR NEW.description <> OLD.description) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', NEW.username), 'B') ||
                setweight(to_tsvector('english', NEW.description), 'C')
            );
        END IF;
    END IF;

    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER user_search_update
BEFORE INSERT OR UPDATE ON "user"
FOR EACH ROW
EXECUTE PROCEDURE user_search_update();

CREATE INDEX search_user ON "user" USING GIN (tsvectors);

ALTER TABLE post ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = setweight(to_tsvector('english', NEW.content), 'A');
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.content <> OLD.content) THEN
            NEW.tsvectors = setweight(to_tsvector('english', NEW.content), 'A');
        END IF;
    END IF;

    RETURN NEW;
END $$
LANGUAGE plpgsql;


CREATE FUNCTION insert_follows_client_notification() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
    follower_name TEXT;
BEGIN
    -- Get the name of the user who started following
    SELECT name INTO follower_name
    FROM "user"
    WHERE id = NEW.followed_client_id;

    -- Insert into notification table with the follower's name in the content
    INSERT INTO notification (content, viewed, user_id)
    VALUES (follower_name || ' accepted your follow request', FALSE, NEW.sender_client_id)
    RETURNING id INTO notification_id;

    -- Insert into general_notification table
    INSERT INTO general_notification (id)
    VALUES (notification_id);

    INSERT INTO started_following_client_notification (id, sender_id)
    VALUES (notification_id, NEW.followed_client_id);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger for inserting into follows_client
CREATE TRIGGER after_insert_follows_client
AFTER INSERT ON follows_client
FOR EACH ROW
EXECUTE PROCEDURE insert_follows_client_notification();

CREATE FUNCTION insert_follows_restaurant_notification() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
    follower_name TEXT;
BEGIN
    -- Get the name of the user who started following
    SELECT name INTO follower_name
    FROM "user"
    WHERE id = NEW.client_id;

    -- Insert into notification table with the follower's name in the content
    INSERT INTO notification (content, viewed, user_id)
    VALUES (follower_name || ' started following you', FALSE, NEW.restaurant_id)
    RETURNING id INTO notification_id;

    -- Insert into general_notification table
    INSERT INTO general_notification (id)
    VALUES (notification_id);

    INSERT INTO started_following_restaurant_notification (id, sender_id)
    VALUES (notification_id, NEW.client_id);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger for inserting into follows_restaurant
CREATE TRIGGER after_insert_follows_restaurant
AFTER INSERT ON follows_restaurant
FOR EACH ROW
EXECUTE PROCEDURE insert_follows_restaurant_notification();

CREATE FUNCTION delete_follows_client_notification() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
BEGIN
    -- Find the notification ID associated with the like
    SELECT n.id INTO notification_id
    FROM notification n
    JOIN general_notification ln ON n.id = ln.id
    JOIN started_following_client_notification lpn ON ln.id = lpn.id
    WHERE lpn.sender_id = OLD.followed_client_id AND n.user_id = OLD.sender_client_id;

    -- Delete the notification if it exists
    IF notification_id IS NOT NULL THEN
        DELETE FROM notification WHERE id = notification_id;
    END IF;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger for deleting from follows_client
CREATE TRIGGER after_delete_follows_client
BEFORE DELETE ON follows_client
FOR EACH ROW
EXECUTE PROCEDURE delete_follows_client_notification();

CREATE FUNCTION delete_follows_restaurant_notification() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
BEGIN
    -- Find the notification ID associated with the like
    SELECT n.id INTO notification_id
    FROM notification n
    JOIN general_notification ln ON n.id = ln.id
    JOIN started_following_restaurant_notification lpn ON ln.id = lpn.id
    WHERE lpn.sender_id = OLD.client_id AND n.user_id = OLD.restaurant_id;

    -- Delete the notification if it exists
    IF notification_id IS NOT NULL THEN
        DELETE FROM notification WHERE id = notification_id;
    END IF;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger for deleting from follows_restaurant
CREATE TRIGGER after_delete_follows_restaurant
BEFORE DELETE ON follows_restaurant
FOR EACH ROW
EXECUTE PROCEDURE delete_follows_restaurant_notification();

CREATE TRIGGER post_search_update
BEFORE INSERT OR UPDATE ON post
FOR EACH ROW
EXECUTE PROCEDURE post_search_update();

CREATE INDEX search_post ON post USING GIN (tsvectors);

ALTER TABLE comment ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION comment_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = setweight(to_tsvector('english', NEW.content), 'A');
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.content <> OLD.content) THEN
            NEW.tsvectors = setweight(to_tsvector('english', NEW.content), 'A');
        END IF;
    END IF;

    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER comment_search_update
BEFORE INSERT OR UPDATE ON comment
FOR EACH ROW
EXECUTE PROCEDURE comment_search_update();

CREATE INDEX search_comment ON comment USING GIN (tsvectors);


ALTER TABLE "group" ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION group_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.description), 'B')
        );
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', NEW.description), 'B')
            );
        END IF;
    END IF;

    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER group_search_update
BEFORE INSERT OR UPDATE ON "group"
FOR EACH ROW
EXECUTE PROCEDURE group_search_update();

CREATE INDEX search_group ON "group" USING GIN (tsvectors);

CREATE FUNCTION verify_like_comment() 
RETURNS TRIGGER AS 
$$
BEGIN
    IF EXISTS (SELECT 1 FROM like_comment 
               WHERE user_id = NEW.user_id AND comment_id = NEW.comment_id) THEN
        RAISE EXCEPTION 'Users can only like a comment once';
    END IF;

    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER verify_like_comment
BEFORE INSERT OR UPDATE ON like_comment
FOR EACH ROW
EXECUTE PROCEDURE verify_like_comment();

CREATE FUNCTION verify_like_post() 
RETURNS TRIGGER AS 
$$
BEGIN
    IF EXISTS (SELECT * FROM like_post WHERE NEW.user_id = user_id AND NEW.post_id = post_id) THEN
    RAISE EXCEPTION 'Users can only like a post once';
    END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;
CREATE TRIGGER verify_like_post
BEFORE INSERT OR UPDATE ON like_post
FOR EACH ROW
EXECUTE PROCEDURE verify_like_post();

CREATE FUNCTION verify_group_request() 
RETURNS TRIGGER AS 
$$
BEGIN
        IF EXISTS (SELECT * FROM join_requests WHERE NEW.client_id = client_id AND NEW.group_id = group_id) THEN
        RAISE EXCEPTION 'Users cannot request to join group they already belong to';
        END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;
CREATE TRIGGER verify_group_request
BEFORE INSERT OR UPDATE ON join_requests
FOR EACH ROW
EXECUTE PROCEDURE verify_group_request();

CREATE FUNCTION verify_group_entry()
RETURNS TRIGGER AS 
$$
BEGIN
    IF EXISTS (SELECT * FROM group_member WHERE NEW.client_id = client_id AND NEW.group_id = group_id) THEN
    RAISE EXCEPTION 'Users cannot join a group they already belong to';
    END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;
CREATE TRIGGER verify_group_entry
BEFORE INSERT OR UPDATE ON group_member
FOR EACH ROW
EXECUTE PROCEDURE verify_group_entry();

CREATE FUNCTION verify_follow_client_request() 
RETURNS TRIGGER AS 
$$
BEGIN
    IF EXISTS (SELECT * FROM request_follow WHERE NEW.requester_client_id = requester_client_id AND NEW.receiver_client_id = receiver_client_id) THEN
    RAISE EXCEPTION 'Users cannot request to follow other users they already follow';
    END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;
CREATE TRIGGER verify_follow_client_request
BEFORE INSERT OR UPDATE ON request_follow
FOR EACH ROW
EXECUTE PROCEDURE verify_follow_client_request();


CREATE FUNCTION verify_follow_client() 

RETURNS TRIGGER AS 
$$
BEGIN
    IF EXISTS (SELECT * FROM follows_client WHERE NEW.sender_client_id = sender_client_id AND NEW.followed_client_id = followed_client_id) THEN
    RAISE EXCEPTION 'Users cannot follow other clients they already follow';
    END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;
CREATE TRIGGER verify_follow_client
BEFORE INSERT OR UPDATE ON follows_client
FOR EACH ROW
EXECUTE PROCEDURE verify_follow_client();


CREATE FUNCTION verify_follow_restaurant() 
RETURNS TRIGGER AS 
$$
BEGIN
    IF EXISTS (SELECT * FROM follows_restaurant WHERE NEW.client_id = client_id AND NEW.restaurant_id = restaurant_id) THEN
    RAISE EXCEPTION 'Users cannot follow restaurants they already follow';
    END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;
CREATE TRIGGER verify_follow_restaurant
BEFORE INSERT OR UPDATE ON follows_restaurant
FOR EACH ROW
EXECUTE PROCEDURE verify_follow_restaurant();

CREATE FUNCTION verify_self_following() 
RETURNS TRIGGER AS 
$$
BEGIN
    IF EXISTS (SELECT * FROM request_follow WHERE NEW.requester_client_id = requester_client_id AND NEW.receiver_client_id = receiver_client_id) THEN
    RAISE EXCEPTION 'Users cannot request to follow themselfs';
    END IF;
    RETURN NEW;
END
$$
LANGUAGE plpgsql;
CREATE TRIGGER verify_self_following
BEFORE INSERT OR UPDATE ON request_follow
FOR EACH ROW
EXECUTE PROCEDURE verify_self_following();

CREATE FUNCTION verify_group_membership() 
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.group_id IS NULL THEN
        RETURN NEW;
    END IF;

    IF NOT EXISTS (
        SELECT 1 
        FROM group_member
        WHERE client_id = NEW.client_id AND group_id = NEW.group_id
    ) THEN
        RAISE EXCEPTION 'User can only post to a group they belong to';
    END IF;

    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER verify_group_membership
BEFORE INSERT ON review_post
FOR EACH ROW
EXECUTE PROCEDURE verify_group_membership();




CREATE FUNCTION check_group_membership_like()
RETURNS TRIGGER AS $$
DECLARE 
    group_member_count INTEGER;
    post_group_id INTEGER;
BEGIN
    SELECT group_id INTO post_group_id
    FROM review_post
    WHERE id = NEW.post_id;

    IF post_group_id IS NULL THEN
        RETURN NEW;
    END IF;

    -- Check if the user is a member of the group
    SELECT COUNT(*)
    INTO group_member_count
    FROM group_member
    WHERE client_id = NEW.user_id
        AND group_id = post_group_id;
    
    IF group_member_count = 0 THEN
        RAISE EXCEPTION 'User is not a member of the group and cannot like this post';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER check_group_membership_like
BEFORE INSERT ON like_post
FOR EACH ROW
EXECUTE FUNCTION check_group_membership_like();


CREATE FUNCTION check_group_membership_comment()
RETURNS TRIGGER AS $$
DECLARE 
    group_member_count INTEGER;
    post_group_id INTEGER;
BEGIN
    -- Check if the post is a review_post and get the group_id
    SELECT group_id INTO post_group_id
    FROM review_post
    WHERE id = NEW.post_id;

    -- If the post is not a review_post or the group_id is null, allow the comment
    IF NOT FOUND OR post_group_id IS NULL THEN
        RETURN NEW;
    END IF;

    -- Check if the user is a member of the group
    SELECT COUNT(*)
    INTO group_member_count
    FROM group_member
    WHERE client_id = NEW.user_id
        AND group_id = post_group_id;
    
    IF group_member_count = 0 THEN
        RAISE EXCEPTION 'User is not a member of the group and cannot comment on this post';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER check_group_membership_comment
BEFORE INSERT ON comment
FOR EACH ROW
EXECUTE FUNCTION check_group_membership_comment();


CREATE FUNCTION add_group_owner_as_member() RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO group_member (client_id, group_id)
    VALUES (NEW.owner_id, NEW.id);
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER add_group_owner_as_member
AFTER INSERT ON "group"
FOR EACH ROW
EXECUTE PROCEDURE add_group_owner_as_member();



CREATE FUNCTION delete_join_requests_after_acceptance() 
RETURNS TRIGGER AS 
$$
BEGIN
    DELETE FROM join_requests
    WHERE client_id = NEW.client_id AND group_id = NEW.group_id;
    RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER delete_join_requests_after_acceptance
AFTER INSERT ON group_member
FOR EACH ROW
EXECUTE PROCEDURE delete_join_requests_after_acceptance();
   

CREATE FUNCTION delete_follow_requests_after_acceptance() 
RETURNS TRIGGER AS $$
BEGIN
DELETE FROM request_follow
WHERE requester_client_id = NEW.sender_client_id AND receiver_client_id = NEW.followed_client_id;
RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER delete_follow_requests_after_acceptance
AFTER INSERT ON follows_client
FOR EACH ROW
EXECUTE PROCEDURE delete_follow_requests_after_acceptance();

CREATE FUNCTION create_follow_notification() RETURNS TRIGGER AS $$
DECLARE
    sender_name TEXT;
    notification_id INTEGER; 
BEGIN
    SELECT name INTO sender_name
    FROM "user"
    WHERE id = NEW.requester_client_id;

    INSERT INTO notification (content, viewed, user_id) 
    VALUES (sender_name || ' has sent you a follow request', FALSE, NEW.receiver_client_id); 

    SELECT currval('notification_id_seq') INTO notification_id;

    INSERT INTO request_notification (id)
    VALUES (notification_id);

    INSERT INTO follow_notification (id, sender_client_id, receiver_client_id)
    VALUES (notification_id, NEW.requester_client_id, NEW.receiver_client_id);

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_insert_request_follow
AFTER INSERT ON request_follow
FOR EACH ROW
EXECUTE PROCEDURE create_follow_notification();

CREATE FUNCTION delete_follow_notification() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
BEGIN
    SELECT n.id INTO notification_id
    FROM notification n
    JOIN request_notification rn ON n.id = rn.id
    JOIN follow_notification fn ON rn.id = fn.id
    WHERE fn.sender_client_id = OLD.requester_client_id AND fn.receiver_client_id = OLD.receiver_client_id;

    IF notification_id IS NOT NULL THEN
        DELETE FROM notification WHERE id = notification_id;
    END IF;

    RETURN OLD;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_delete_request_follow
BEFORE DELETE ON request_follow
FOR EACH ROW
EXECUTE PROCEDURE delete_follow_notification();

CREATE FUNCTION create_join_group_notification() RETURNS TRIGGER AS $$
DECLARE
    requester_name TEXT;
    notification_id INTEGER;
    group_owner_id INTEGER;
BEGIN
    -- Get the name of the requester
    SELECT name INTO requester_name
    FROM "user"
    WHERE id = NEW.client_id;

    -- Get the owner of the group
    SELECT owner_id INTO group_owner_id
    FROM "group"
    WHERE id = NEW.group_id;

    -- Insert the notification for the group owner
    INSERT INTO notification (content, viewed, user_id) 
    VALUES (requester_name || ' has requested to join your group', FALSE, group_owner_id);

    -- Get the ID of the newly inserted notification
    SELECT currval('notification_id_seq') INTO notification_id;

    -- Insert into request_notification
    INSERT INTO request_notification (id)
    VALUES (notification_id);

    -- Insert into join_group_notification
    INSERT INTO join_group_notification (id, client_id, group_id)
    VALUES (notification_id, NEW.client_id, NEW.group_id);

    RETURN NEW;
END
$$ LANGUAGE plpgsql;


CREATE TRIGGER after_insert_join_requests
AFTER INSERT ON join_requests
FOR EACH ROW
EXECUTE PROCEDURE create_join_group_notification();

CREATE FUNCTION delete_join_group_notification() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;  
BEGIN
    -- Find the notification ID associated with the join group request
    SELECT n.id INTO notification_id
    FROM notification n
    JOIN request_notification rn ON n.id = rn.id
    JOIN join_group_notification jgn ON rn.id = jgn.id
    WHERE jgn.client_id = OLD.client_id AND jgn.group_id = OLD.group_id;

    -- Delete the notification if it exists
    IF notification_id IS NOT NULL THEN
        DELETE FROM notification WHERE id = notification_id;
    END IF;

    RETURN OLD;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_delete_join_requests
BEFORE DELETE ON join_requests
FOR EACH ROW
EXECUTE PROCEDURE delete_join_group_notification();

CREATE FUNCTION create_like_comment_notification() RETURNS TRIGGER AS $$
DECLARE
    comment_owner_id INTEGER;
    liker_name TEXT;
    notification_id INTEGER;  
BEGIN
    SELECT name INTO liker_name
    FROM "user"
    WHERE id = NEW.user_id;

    SELECT user_id INTO comment_owner_id
    FROM comment
    WHERE id = NEW.comment_id;

    IF NEW.user_id = comment_owner_id THEN
        RETURN NEW;
    END IF;

    INSERT INTO notification (content, viewed, user_id) 
    VALUES (liker_name || ' liked your comment', FALSE, (SELECT user_id FROM comment WHERE id = NEW.comment_id)); 

    SELECT currval('notification_id_seq') INTO notification_id;

    INSERT INTO like_notification (id)
    VALUES (notification_id);

    INSERT INTO like_comment_notification (id, user_id, comment_id)
    VALUES (notification_id, NEW.user_id, NEW.comment_id);

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_insert_like_comment
AFTER INSERT ON like_comment
FOR EACH ROW
EXECUTE PROCEDURE create_like_comment_notification();

CREATE FUNCTION delete_like_comment_notification() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
BEGIN
    -- Find the notification ID associated with the like comment
    SELECT n.id INTO notification_id
    FROM notification n
    JOIN like_notification ln ON n.id = ln.id
    JOIN like_comment_notification lcn ON ln.id = lcn.id
    WHERE lcn.user_id = OLD.user_id AND lcn.comment_id = OLD.comment_id;

    -- Delete the notification if it exists
    IF notification_id IS NOT NULL THEN
        DELETE FROM notification WHERE id = notification_id;
    END IF;

    RETURN OLD;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_delete_like_comment
BEFORE DELETE ON like_comment
FOR EACH ROW
EXECUTE PROCEDURE delete_like_comment_notification();

CREATE FUNCTION create_like_post_notification() RETURNS TRIGGER AS $$
DECLARE
    liker_name TEXT;
    notification_id INTEGER; 
    post_owner_id INTEGER;   
BEGIN
    SELECT name INTO liker_name
    FROM "user"
    WHERE id = NEW.user_id;

    IF EXISTS (SELECT 1 FROM review_post WHERE id = NEW.post_id) THEN
        SELECT client_id INTO post_owner_id
        FROM review_post
        WHERE id = NEW.post_id;
    ELSE
        SELECT restaurant_id INTO post_owner_id
        FROM informational_post
        WHERE id = NEW.post_id;
    END IF;
    IF post_owner_id = NEW.user_id THEN
        RETURN NEW;
    END IF;

    INSERT INTO notification (content, viewed, user_id) 
    VALUES (liker_name || ' liked your post', FALSE, post_owner_id); 

    SELECT currval('notification_id_seq') INTO notification_id;

    INSERT INTO like_notification (id)
    VALUES (notification_id);

    INSERT INTO like_post_notification (id, user_id, post_id)
    VALUES (notification_id, NEW.user_id, NEW.post_id);

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_insert_like_post
AFTER INSERT ON like_post
FOR EACH ROW
EXECUTE PROCEDURE create_like_post_notification();

-- Create the function to handle deletion of like post notifications with debugging
CREATE FUNCTION delete_like_post_notification() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
BEGIN
    -- Find the notification ID associated with the like
    SELECT n.id INTO notification_id
    FROM notification n
    JOIN like_notification ln ON n.id = ln.id
    JOIN like_post_notification lpn ON ln.id = lpn.id
    WHERE lpn.user_id = OLD.user_id AND lpn.post_id = OLD.post_id;

    -- Delete the notification if it exists
    IF notification_id IS NOT NULL THEN
        DELETE FROM notification WHERE id = notification_id;
    END IF;

    RETURN OLD;
END
$$ LANGUAGE plpgsql;

-- Create the trigger for deleting like post notifications
CREATE TRIGGER after_delete_like_post
BEFORE DELETE ON like_post
FOR EACH ROW
EXECUTE PROCEDURE delete_like_post_notification();


CREATE FUNCTION create_group_notification_after_post() RETURNS TRIGGER AS $$
DECLARE
    group_name TEXT;
    member_id INTEGER;
    notification_id INTEGER;  
BEGIN
    SELECT name INTO group_name
    FROM "group"
    WHERE id = NEW.group_id;

    FOR member_id IN
        SELECT client_id
        FROM group_member
        WHERE group_id = NEW.group_id
    LOOP
        INSERT INTO notification (content, viewed, user_id) 
        VALUES ('A post has been made in the group ' || group_name, FALSE, member_id);

        SELECT currval('notification_id_seq') INTO notification_id;

        INSERT INTO general_notification (id)
        VALUES (notification_id);

        INSERT INTO group_notification (id, group_id)
        VALUES (notification_id, NEW.group_id);
    END LOOP;

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_insert_review_post
AFTER INSERT ON review_post
FOR EACH ROW
EXECUTE PROCEDURE create_group_notification_after_post();

CREATE FUNCTION delete_group_notification_after_post() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
BEGIN
    -- Find the notification ID associated with the group post
    FOR notification_id IN
        SELECT n.id
        FROM notification n
        JOIN group_notification gn ON n.id = gn.id
        WHERE gn.group_id = OLD.group_id
    LOOP
        -- Delete the notification if it exists
        DELETE FROM notification WHERE id = notification_id;
    END LOOP;

    RETURN OLD;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_delete_review_post
BEFORE DELETE ON review_post
FOR EACH ROW
EXECUTE PROCEDURE delete_group_notification_after_post();


CREATE FUNCTION create_group_notification_after_join() RETURNS TRIGGER AS $$
DECLARE
    group_name TEXT;
    member_name TEXT;
    member_id INTEGER;
    notification_id INTEGER;
    group_owner_id INTEGER;
BEGIN
    -- Get the name of the group
    SELECT name INTO group_name
    FROM "group"
    WHERE id = NEW.group_id;

    -- Get the name of the new member
    SELECT name INTO member_name
    FROM "user"
    WHERE id = NEW.client_id;

    -- Get the owner of the group
    SELECT owner_id INTO group_owner_id
    FROM "group"
    WHERE id = NEW.group_id;

    -- Skip notification if the new member is the owner of the group
    IF NEW.client_id = group_owner_id THEN
        RETURN NEW;
    END IF;

    -- Loop through all members of the group
    FOR member_id IN
        SELECT client_id
        FROM group_member
        WHERE group_id = NEW.group_id and client_id != NEW.client_id
    LOOP
        INSERT INTO notification (content, viewed, user_id) 
        VALUES (member_name || ' has joined the group ' || group_name, FALSE, member_id);

        SELECT currval('notification_id_seq') INTO notification_id;

        INSERT INTO general_notification (id)
        VALUES (notification_id);

        INSERT INTO group_notification (id, group_id)
        VALUES (notification_id, NEW.group_id);
    END LOOP;

    INSERT INTO notification (content, viewed, user_id) 
        VALUES ('You have joined the group ' || group_name, FALSE, NEW.client_id);

        SELECT currval('notification_id_seq') INTO notification_id;

        INSERT INTO general_notification (id)
        VALUES (notification_id);

        INSERT INTO group_notification (id, group_id)
        VALUES (notification_id, NEW.group_id);

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_insert_groupmember
AFTER INSERT ON group_member
FOR EACH ROW
EXECUTE PROCEDURE create_group_notification_after_join();


CREATE FUNCTION create_group_notification_after_leave() RETURNS TRIGGER AS $$
DECLARE
    group_name TEXT;
    member_name TEXT;
    member_id INTEGER;
    notification_id INTEGER;
BEGIN
    IF (SELECT owner_id FROM "group" WHERE id = OLD.group_id) = OLD.client_id THEN
        DELETE FROM "group" WHERE id = OLD.group_id;
        RETURN OLD;
    END IF;

    SELECT name INTO group_name
    FROM "group"
    WHERE id = OLD.group_id;

    SELECT name INTO member_name
    FROM "user"
    WHERE id = OLD.client_id;

    FOR member_id IN
        SELECT client_id
        FROM group_member  
        WHERE group_id = OLD.group_id
    LOOP
        INSERT INTO notification (content, viewed, user_id) 
        VALUES (member_name || ' has left the group ' || group_name, FALSE, member_id);

        SELECT currval('notification_id_seq') INTO notification_id;

        INSERT INTO general_notification (id)
        VALUES (notification_id);

        INSERT INTO group_notification (id, group_id)
        VALUES (notification_id, OLD.group_id);
    END LOOP;

    FOR notification_id IN
        SELECT n.id
        FROM notification n
        JOIN group_notification gn ON n.id = gn.id
        WHERE gn.group_id = OLD.group_id
    LOOP
        DELETE FROM notification WHERE id = notification_id;
    END LOOP;

    RETURN OLD;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_delete_groupmember
AFTER DELETE ON group_member
FOR EACH ROW
EXECUTE PROCEDURE create_group_notification_after_leave();


CREATE FUNCTION create_admin_notification_after_block() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
BEGIN
    INSERT INTO notification (content, viewed, user_id) 
    VALUES ('You have been blocked', FALSE, NEW.id);

    SELECT currval('notification_id_seq') INTO notification_id;

    INSERT INTO general_notification (id)
    VALUES (notification_id);

    INSERT INTO admin_notification (id)
    VALUES (notification_id);

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_block_user
AFTER UPDATE OF is_blocked ON "user"
FOR EACH ROW
WHEN (NEW.is_blocked = TRUE AND OLD.is_blocked = FALSE)
EXECUTE PROCEDURE create_admin_notification_after_block();

CREATE FUNCTION delete_admin_notification_after_block() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
BEGIN
    -- Find the notification ID associated with the admin block
    SELECT n.id INTO notification_id
    FROM notification n
    JOIN admin_notification an ON n.id = an.id
    WHERE n.user_id = OLD.id;

    -- Delete the notification if it exists
    IF notification_id IS NOT NULL THEN
        DELETE FROM notification WHERE id = notification_id;
    END IF;

    RETURN OLD;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_unblock_user
AFTER UPDATE OF is_blocked ON "user"
FOR EACH ROW
WHEN (NEW.is_blocked = FALSE AND OLD.is_blocked = TRUE)
EXECUTE PROCEDURE delete_admin_notification_after_block();

CREATE FUNCTION create_comment_notification() RETURNS TRIGGER AS $$
DECLARE
    commenter_name TEXT;
    notification_id INTEGER;
    post_owner_id INTEGER;
BEGIN
    -- Get the name of the commenter
    SELECT name INTO commenter_name
    FROM "user"
    WHERE id = NEW.user_id;

    -- Get the owner of the post
    IF EXISTS (SELECT 1 FROM review_post WHERE id = NEW.post_id) THEN
        SELECT client_id INTO post_owner_id
        FROM review_post
        WHERE id = NEW.post_id;
    ELSE
        SELECT restaurant_id INTO post_owner_id
        FROM informational_post
        WHERE id = NEW.post_id;
    END IF;

    IF post_owner_id = NEW.user_id THEN
        RETURN NEW;
    END IF;

    -- Insert the notification for the post owner
    INSERT INTO notification (content, viewed, user_id) 
    VALUES (commenter_name || ' commented on your post', FALSE, post_owner_id);

    -- Get the ID of the newly inserted notification
    SELECT currval('notification_id_seq') INTO notification_id;

    -- Insert into comment_notification
    INSERT INTO comment_notification (id, comment_id)
    VALUES (notification_id, NEW.id);

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_insert_comment
AFTER INSERT ON comment
FOR EACH ROW
EXECUTE PROCEDURE create_comment_notification();

CREATE FUNCTION delete_comment_notification() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
BEGIN
    -- Find the notification ID associated with the comment
    SELECT n.id INTO notification_id
    FROM notification n
    JOIN comment_notification cn ON n.id = cn.id
    WHERE cn.comment_id = OLD.id;

    -- Delete the notification if it exists
    IF notification_id IS NOT NULL THEN
        DELETE FROM notification WHERE id = notification_id;
    END IF;

    RETURN OLD;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER after_delete_comment
BEFORE DELETE ON comment
FOR EACH ROW
EXECUTE PROCEDURE delete_comment_notification();


-- Create the function to notify the invited client
CREATE FUNCTION notify_client_of_group_invitation() RETURNS TRIGGER AS $$
DECLARE
    group_name TEXT;
    owner_name TEXT;
    notification_id INTEGER;
BEGIN
    -- Get the name of the group
    SELECT name INTO group_name
    FROM "group"
    WHERE id = NEW.group_id;

    -- Get the name of the group owner
    SELECT name INTO owner_name
    FROM "user"
    WHERE id = (SELECT owner_id FROM "group" WHERE id = NEW.group_id);

    -- Insert the notification for the invited client
    INSERT INTO notification (content, viewed, user_id) 
    VALUES (owner_name || ' invited you to join the group ' || group_name, FALSE, NEW.client_id);

    -- Get the ID of the newly inserted notification
    SELECT currval('notification_id_seq') INTO notification_id;

    -- Insert into general_notification
    INSERT INTO general_notification (id)
    VALUES (notification_id);

    -- Insert into group_notification
    INSERT INTO group_notification (id, group_id)
    VALUES (notification_id, NEW.group_id);

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

-- Create the trigger for notifying the invited client
CREATE TRIGGER after_insert_group_invitation
AFTER INSERT ON group_invitations
FOR EACH ROW
EXECUTE PROCEDURE notify_client_of_group_invitation();

-- Create the function to notify the invited client
CREATE FUNCTION cancel_notification_client_of_group_invitation() RETURNS TRIGGER AS $$
DECLARE
    notification_id INTEGER;
BEGIN
    -- Find the notification ID associated with the group invitation
    SELECT n.id INTO notification_id
    FROM notification n
    JOIN group_notification gn ON gn.id = n.id
    WHERE gn.group_id = OLD.group_id AND n.user_id = OLD.client_id;

    -- Delete the notification if it exists
    IF notification_id IS NOT NULL THEN
        DELETE FROM notification WHERE id = notification_id;
    END IF;

    RETURN OLD;
END
$$ LANGUAGE plpgsql;

-- Create the trigger for notifying the invited client
CREATE TRIGGER after_delete_group_invitation
BEFORE DELETE ON group_invitations
FOR EACH ROW
EXECUTE PROCEDURE cancel_notification_client_of_group_invitation();

-- Create the function to notify the group owner of status change
CREATE FUNCTION notify_owner_of_invitation_status_change() RETURNS TRIGGER AS $$
DECLARE
    client_name TEXT;
    group_name TEXT;
    owner_id INTEGER;
    notification_id INTEGER;
    status TEXT;
BEGIN
    -- Get the name of the client
    SELECT name INTO client_name
    FROM "user"
    WHERE id = OLD.client_id;

    -- Get the name of the group
    SELECT name INTO group_name
    FROM "group"
    WHERE id = OLD.group_id;

    -- Get the owner of the group
    SELECT "group".owner_id INTO owner_id
    FROM "group"
    WHERE id = OLD.group_id;

    -- Determine the status
    IF NEW.status = 'accepted' THEN
        status := 'accepted your invitation to join the group';
    ELSE
        status := 'rejected your invitation to join the group';
    END IF;

    -- Insert the notification for the group owner
    INSERT INTO notification (content, viewed, user_id) 
    VALUES (client_name || ' has ' || status || ' ' || group_name, FALSE, owner_id);

    -- Get the ID of the newly inserted notification
    SELECT currval('notification_id_seq') INTO notification_id;

    -- Insert into general_notification
    INSERT INTO general_notification (id)
    VALUES (notification_id);

    -- Insert into group_notification
    INSERT INTO group_notification (id, group_id)
    VALUES (notification_id, OLD.group_id);

    RETURN NEW;
END
$$ LANGUAGE plpgsql;

-- Create the trigger for notifying the group owner
CREATE TRIGGER after_update_group_invitation_status
AFTER UPDATE OF status ON group_invitations
FOR EACH ROW
EXECUTE PROCEDURE notify_owner_of_invitation_status_change();

CREATE OR REPLACE FUNCTION handle_user_unblock() RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM notification
    WHERE id IN (
        SELECT n.id
        FROM notification n
        JOIN appeal_unblock_notification au ON au.id = n.id
        WHERE au.user_blocked_id = OLD.id
    );

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger
CREATE TRIGGER user_unblock_trigger
AFTER UPDATE OF is_blocked ON "user"
FOR EACH ROW
WHEN (OLD.is_blocked = true AND NEW.is_blocked = false)
EXECUTE FUNCTION handle_user_unblock();