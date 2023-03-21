<?php

use _Interfaces\IUser;

class User implements IUser
{
    //region Properties
    private IDataSource $dataSource;
    private int $id;
    private string $username;
    private string $password;
    private string $email;
    private ?string $pfpPath;
    private bool $admin;
    private bool $canComment;
    //endregion

    //region Ctor
    function __construct(IDataSource $dataSource, int $id, string $username, string $password, string $email,
                         ?string $pfpPath, bool $admin, bool $canComment) {
        $this->dataSource = $dataSource;
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->pfpPath = $pfpPath;
        $this->admin = $admin;
        $this->canComment = $canComment;
    }
    //endregion

    //region Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPasswordHash(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getProfilePicturePath(): ?string
    {
        return $this->pfpPath;
    }

    public function getFriends(): array
    {
        try {
            $this->dataSource->updateUser($this);
        } catch (Exception $exception) {
            throw new InvalidArgumentException("Could not update user", $exception);
        }

        return $this->dataSource->getFriends($this);
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function canComment(): bool
    {
        return $this->canComment;
    }
    //endregion

    //region Friends
    public function addFriend(IUser $user)
    {
        try {
            $this->dataSource->addFriend($this, $user);
        } catch (Exception $exception) {
            throw new Exception("Could not add friend", $exception);
        }
    }

    public function removeFriend(IUser $user)
    {
        try {
            $this->dataSource->removeFriend($this, $user);
        } catch (Exception $exception) {
            throw new Exception("Could not add friend", $exception);
        }
    }
    //endregion

    //region Comment
    public function writeComment(\_Interfaces\IShow $show, string $content) {
        if (!$this->canComment) {
            throw new Exception("User [$this->username] is not allowed to comment");
        }

        try {
            $this->dataSource->addComment($this, $show, $content);
        } catch (Exception $exception) {
            throw new Exception('Could not write comment', $exception);
        }
    }

    public function removeComment(\_Interfaces\IComment $comment)
    {
        if (!$this->admin || $comment->getAuthor()->getId() != $this->getId()) {
            throw new Exception('User can\'t remove this comment');
        }

        try {
            $this->dataSource->removeComment($comment);
        } catch (Exception $exception) {
            throw new Exception('Could not remove comment', $exception);
        }
    }
    //endregion

    //region Show
    public function addShowToList(\_Interfaces\IShow $show)
    {
        try {
            $this->dataSource->addWatching($this, $show);
        } catch (Exception $exception) {
            throw new Exception('Could not add show to list', $exception);
        }
    }

    public function updateRating(\_Interfaces\IShow $show, ?int $episodesWatched, ?int $rating)
    {
        try {
            $this->dataSource->updateWatching($this, $show, $episodesWatched, $rating);
        } catch (Exception $exception) {
            throw new Exception('Could not update rating', $exception);
        }
    }

    public function removeShowFromList(\_Interfaces\IShow $show)
    {
        try {
            $this->dataSource->removeWatching($this, $show);
        } catch (Exception $exception) {
            throw new Exception('Could not remove show', $exception);
        }
    }
    //endregion

    //region Public Methods
    public function update(?string $username, ?string $email, ?string $profilePicturePath, ?string $passwordHash)
    {
        try {
            $this->dataSource->updateUser($this);
        } catch (Exception $exception) {
            throw new Exception("Could not query friends", $exception);
        }
    }

    public function remove() {
        try {
            $this->dataSource->removeUser($this);
        } catch (Exception $exception) {
            throw new InvalidArgumentException("Could not remove user", $exception);
        }
    }
    //endregion
}