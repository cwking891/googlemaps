# By using the symbol ':user', we get Factory Girl to simulate the User model.

Factory.define :user do |user|
  user.name                  "Signor Wences"
  user.email                 "afinefellow@net.com"
  user.password              "mypassword"
  user.password_confirmation "mypassword"
end


Factory.sequence :email do |n|
  "person-#{n}@example.com"
end

Factory.define :micropost do |micropost|
  micropost.content          "Here is some content"
  micropost.association      :user
end